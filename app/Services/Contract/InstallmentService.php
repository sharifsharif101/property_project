<?php

namespace App\Services\Contract;

use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // ✅ إضافة: لاستخدام نظام التسجيل
use InvalidArgumentException;
use RuntimeException; // ✅ إضافة: لرمي أخطاء وقت التشغيل

class InstallmentService
{
    /**
     * يولد الأقساط لعقد جديد بعد التحقق من صحة بياناته.
     */
    public function generateForContract(Contract $contract)
    {
        // ✅ [الحل 2 & 4] التحقق الشامل من صحة بيانات العقد قبل البدء
        if (!$contract->start_date || !$contract->end_date) {
            throw new InvalidArgumentException("تواريخ العقد (#{$contract->id}) غير مكتملة.");
        }
        if (!$contract->rent_amount || !is_numeric($contract->rent_amount) || $contract->rent_amount <= 0) {
            throw new InvalidArgumentException("مبلغ الإيجار للعقد (#{$contract->id}) يجب أن يكون رقمًا أكبر من صفر.");
        }

        try {
            $startDate = Carbon::parse($contract->start_date);
            $endDate = Carbon::parse($contract->end_date);
        } catch (\Exception $e) {
            throw new InvalidArgumentException("صيغة تاريخ العقد (#{$contract->id}) غير صالحة.");
        }

        if ($startDate->greaterThanOrEqualTo($endDate)) {
            throw new InvalidArgumentException("تاريخ بداية العقد (#{$contract->id}) يجب أن يكون قبل تاريخ النهاية.");
        }

        // ✅ [الحل 6] معالجة الأخطاء المحتملة أثناء عملية الحفظ في قاعدة البيانات
        try {
            DB::transaction(function () use ($contract, $startDate, $endDate) {
                // تمرير endDate لتجنب إعادة تحليله
                $this->generate($contract, $startDate, $endDate);
            });
        } catch (\Exception $e) {
            Log::error("فشل توليد الأقساط للعقد #{$contract->id}: " . $e->getMessage(), ['exception' => $e]);
            throw new RuntimeException("حدث خطأ فني أثناء توليد الأقساط للعقد #{$contract->id}.");
        }
    }

    /**
     * يعيد توليد الأقساط (عادة ما يتم استدعاؤها من الـ Observer).
     */
    public function regenerateForContract(Contract $contract, Carbon $generationStartDate)
    {
        // يمكن إضافة نفس منطق التحقق هنا إذا كان هناك احتمال لاستدعائها مباشرة
        $this->generateForContract($contract); // الطريقة الأبسط هي استدعاء الدالة الرئيسية التي تحتوي على كل التحققات
    }

    /**
     * الدالة الخاصة التي تقوم بعملية التوليد الفعلية.
     */
    private function generate(Contract $contract, Carbon $startDate, Carbon $endDate)
    {
        $currentDate = $startDate->copy();
        $lastInstallmentDate = null;

        // --- الجزء الأول: توليد الأقساط الكاملة ---
        while (true) {
            $periodStartDate = $currentDate->copy();
            $periodEndDate = $periodStartDate->copy();
            
            // تحديد نهاية الفترة القادمة
            switch ($contract->rent_type) {
                case 'daily': $periodEndDate->addDay(); break;
                case 'weekly': $periodEndDate->addWeek(); break;
                case 'monthly': $periodEndDate->addMonthsNoOverflow(); break;
                case 'annually': $periodEndDate->addYear(); break;
                default: throw new InvalidArgumentException("نوع الإيجار '{$contract->rent_type}' غير مدعوم.");
            }

            // إذا كانت نهاية الفترة تتجاوز نهاية العقد، توقف عن إنشاء أقساط كاملة
            if ($periodEndDate->greaterThan($endDate)) {
                $lastInstallmentDate = $periodStartDate; // احتفظ بتاريخ بداية الفترة الجزئية
                break; // اخرج من الحلقة
            }

            // ✅ [الحل 5] التحقق من عدم وجود القسط قبل إنشائه
            if (!$contract->rentInstallments()->where('due_date', $periodStartDate->toDateString())->exists()) {
                $contract->rentInstallments()->create([
                    'due_date' => $periodStartDate->toDateString(), 
                    'amount_due' => $contract->rent_amount, 
                    'status' => 'Due',
                ]);
            }
            $currentDate = $periodEndDate;
        }

        // --- الجزء الثاني: توليد القسط الجزئي (النسبي) إن وجد ---
        if ($lastInstallmentDate && $lastInstallmentDate->lessThan($endDate)) {
            $remainingDays = $lastInstallmentDate->diffInDays($endDate);

            if ($remainingDays > 0) {
                
                // تحديد عدد أيام الفترة الكاملة لحساب المعدل اليومي بدقة
                $basePeriodEndDate = $lastInstallmentDate->copy();
                switch ($contract->rent_type) {
                    case 'daily': $basePeriodEndDate->addDay(); break;
                    case 'weekly': $basePeriodEndDate->addWeek(); break;
                    case 'monthly': $basePeriodEndDate->addMonthsNoOverflow(); break;
                    case 'annually': $basePeriodEndDate->addYear(); break;
                }
                
                // ✅ [الحل 1 & 8] الحساب الدقيق لأيام الفترة باستخدام الفرق الفعلي بين تاريخين
                $basePeriodDays = $lastInstallmentDate->diffInDays($basePeriodEndDate);
                
                // حماية من القسمة على صفر في حالات نادرة جداً
                if($basePeriodDays == 0) $basePeriodDays = 1; 
                
                $perDayAmount = $contract->rent_amount / $basePeriodDays;
                $proratedAmount = round($perDayAmount * $remainingDays, 2);

                if ($proratedAmount > 0 && !$contract->rentInstallments()->where('due_date', $lastInstallmentDate->toDateString())->exists()) {
                    $contract->rentInstallments()->create([
                        'due_date' => $lastInstallmentDate->toDateString(),
                        'amount_due' => $proratedAmount,
                        'status' => 'Due',
                        'notes' => "قسط جزئي لعدد {$remainingDays} أيام متبقية.",
                    ]);
                }
            }
        }
    }
}