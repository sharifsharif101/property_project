<?php

namespace App\Services\Contract;

use App\Models\Contract;
use App\Models\RentInstallment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * هذا الـ Service مسؤول عن كل العمليات المنطقية المتعلقة بأقساط الإيجار.
 * وظيفته الأساسية هي إنشاء جدول الأقساط لعقد معين.
 */
class InstallmentService
{
    /**
     * يقوم بإنشاء جدول الأقساط لعقد معين بناءً على نوع الإيجار.
     * يتعامل مع الأنواع: 'daily', 'weekly', 'monthly', 'yearly'.
     *
     * @param Contract $contract العقد الذي سيتم إنشاء الأقساط له.
     * @return void
     */
    public function generateForContract(Contract $contract): void
    {
        // استخدام Transaction لضمان أنه إما يتم إنشاء كل الأقساط بنجاح، أو لا يتم إنشاء أي شيء في حالة حدوث خطأ.
        DB::transaction(function () use ($contract) {
            
            // تسجيل بداية العملية في ملف اللوج للتشخيص
            Log::info("Starting installment generation for Contract ID: {$contract->id}");

            // 1. حذف الأقساط القديمة أولاً (هذا السطر حاسم جداً عند تحديث عقد موجود)
            $contract->rentInstallments()->delete();
            Log::info("Deleted old installments for Contract ID: {$contract->id}");

            // 2. إعداد متغيرات التواريخ باستخدام مكتبة Carbon لسهولة التعامل معها
            $startDate = Carbon::parse($contract->start_date);
            $endDate = Carbon::parse($contract->end_date);
            
            // إنشاء نسخة من تاريخ البداية للعمل عليها داخل الحلقة
            $currentDate = $startDate->copy();

            // 3. التحقق من أن تاريخ النهاية ليس قبل تاريخ البداية
            if ($endDate->isBefore($startDate)) {
                Log::warning("End date is before start date for Contract ID: {$contract->id}. Aborting generation.");
                return; // إيقاف التنفيذ إذا كانت التواريخ غير منطقية
            }

            // 4. حلقة لإنشاء الأقساط طالما أن التاريخ الحالي لم يتجاوز تاريخ النهاية
            while ($currentDate->lessThanOrEqualTo($endDate)) {
                
                // إنشاء سجل قسط جديد في قاعدة البيانات
                RentInstallment::create([
                    'contract_id' => $contract->id,
                    'due_date'    => $currentDate->toDateString(), // حفظ التاريخ بصيغة Y-m-d
                    'amount_due'  => $contract->rent_amount,
                    'status'      => 'Due', // الحالة الافتراضية عند الإنشاء
                ]);

                // 5. الانتقال إلى تاريخ الاستحقاق التالي بناءً على نوع الإيجار المحدد في العقد
                switch ($contract->rent_type) {
                    case 'daily':
                        $currentDate->addDay();
                        break;
                    
                    case 'weekly':
                        $currentDate->addWeek();
                        break;
                    
                    case 'monthly':
                        $currentDate->addMonth();
                        break;
                    
                    case 'yearly':
                        $currentDate->addYear();
                        break;

                    default:
                        // إذا كان النوع غير معروف، سجل خطأ وأوقف الحلقة لمنع حلقة لا نهائية (Infinite Loop)
                        Log::error("Unknown rent_type '{$contract->rent_type}' for Contract ID: {$contract->id}. Stopping loop.");
                        // استخدام `break 2;` للخروج من الـ switch والـ while معاً
                        break 2; 
                }
            }
            
            Log::info("Successfully generated installments for Contract ID: {$contract->id} with type '{$contract->rent_type}'.");
        });
    }
}