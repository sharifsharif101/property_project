<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\RentInstallment;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PaymentService
{
    public function recordPayment(array $data)
    {
        return DB::transaction(function () use ($data) {
            $installment = RentInstallment::findOrFail($data['rent_installment_id']);

            $paidAmount = (float) $data['amount'];

            // ✅✅✅ حل مشكلة الأرقام العشرية: نقارن بعد التقريب ✅✅✅
            $remainingAmount = $installment->amount_due + $installment->late_fee - $installment->amount_paid;
            
            // اسمح بهامش خطأ بسيط جداً أو استخدم التقريب
            if (round($paidAmount, 2) > round($remainingAmount, 2)) {
                throw new InvalidArgumentException('المبلغ المدفوع أكبر من المبلغ المتبقي على القسط.');
            }

            // 1. تسجيل الدفعة نفسها
            Payment::create([
                'contract_id' => $installment->contract_id,
                'rent_installment_id' => $installment->id,
                'amount' => $paidAmount,
                'payment_date' => $data['payment_date'],
                'payment_method' => $data['payment_method'],
                'transaction_reference' => $data['transaction_reference'],
                'notes' => $data['notes'],
            ]);

            // 2. تحديث القسط
            $installment->amount_paid += $paidAmount;

            // حساب المتبقي من جديد بعد الدفعة
            $newRemaining = $installment->amount_due + $installment->late_fee - $installment->amount_paid;

            // تحديث حالة القسط
            if (round($newRemaining, 2) <= 0) {
                $installment->status = 'Paid';
            } else {
                $installment->status = 'Partially Paid';
            }

            $installment->save();

            return $installment;
        });
    }
}