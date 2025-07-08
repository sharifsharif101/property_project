<?php

namespace App\Services\Contract;

use App\Models\Contract;
use App\Models\RentInstallment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InstallmentService
{
    public function generateForContract(Contract $contract)
    {
        // استخدام Transaction لضمان إنشاء كل الأقساط أو لا شيء
        DB::transaction(function () use ($contract) {
            $startDate = Carbon::parse($contract->start_date);
            $endDate = Carbon::parse($contract->end_date);
            $currentDate = $startDate->copy();

            // سنفترض الآن أن الدفع "شهري" كمثال
            if ($contract->rent_type === 'monthly') {
                while ($currentDate->lessThanOrEqualTo($endDate)) {
                    RentInstallment::create([
                        'contract_id' => $contract->id,
                        'due_date' => $currentDate->toDateString(),
                        'amount_due' => $contract->rent_amount,
                        'status' => 'Due',
                    ]);

                    // الانتقال إلى الشهر التالي
                    $currentDate->addMonth();
                }
            }
            // يمكنك إضافة حالات أخرى هنا (سنوي، ربع سنوي ..إلخ)
            // elseif ($contract->rent_type === 'yearly') { ... }
        });
    }
}