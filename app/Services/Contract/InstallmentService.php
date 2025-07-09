<?php

namespace App\Services\Contract;

use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InstallmentService
{
    /**
     * يولد الأقساط لعقد جديد.
     */
    public function generateForContract(Contract $contract)
    {
        $this->generate($contract, Carbon::parse($contract->start_date));
    }

    /**
     * يعيد توليد الأقساط لعقد تم تحديثه من تاريخ معين.
     */
    public function regenerateForContract(Contract $contract, Carbon $generationStartDate)
    {
        $this->generate($contract, $generationStartDate);
    }

    /**
     * الدالة الأساسية التي تقوم بعملية التوليد الفعلية.
     */
    private function generate(Contract $contract, Carbon $startDate)
    {
        DB::transaction(function () use ($contract, $startDate) {
            
            $endDate = Carbon::parse($contract->end_date);
            $currentDate = $startDate->copy();

            // تأكد من أن تاريخ البدء ليس بعد تاريخ النهاية
            if ($currentDate->greaterThan($endDate)) {
                return; // لا تفعل شيئاً
            }

            if ($contract->rent_type === 'monthly') {
                while ($currentDate->lessThanOrEqualTo($endDate)) {
                    // تحقق من عدم وجود قسط بنفس التاريخ بالفعل (لتجنب التكرار)
                    $exists = $contract->rentInstallments()->where('due_date', $currentDate->toDateString())->exists();
                    
                    if (!$exists) {
                        $contract->rentInstallments()->create([
                            'due_date' => $currentDate->toDateString(),
                            'amount_due' => $contract->rent_amount,
                            'status' => 'Due',
                        ]);
                    }

                    $currentDate->addMonth();
                }
            }
            // ... يمكنك إضافة منطق لأنواع الإيجار الأخرى هنا ...
        });
    }
}