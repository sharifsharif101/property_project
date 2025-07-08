<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\RentInstallment;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PaymentService
{
    /**
     * يسجل دفعة جديدة ويقوم بتحديث القسط المرتبط بها.
     * @param array $data بيانات الدفعة المدخلة من النموذج.
     * @return Payment
     */
    public function recordPayment(array $data): Payment
    {
        // استخدام Transaction لضمان سلامة البيانات
        return DB::transaction(function () use ($data) {
            $installment = RentInstallment::findOrFail($data['rent_installment_id']);

            // تحقق من أن المبلغ المدفوع لا يتجاوز المبلغ المتبقي
            $remaining_due = $installment->amount_due + $installment->late_fee - $installment->amount_paid;
            if ($data['amount'] > $remaining_due) {
                // يمكنك التعامل مع هذا الخطأ بطريقة أفضل، لكننا سنلقي استثناءً الآن
                throw new InvalidArgumentException('المبلغ المدفوع أكبر من المبلغ المتبقي على القسط.');
            }

            // 1. إنشاء سجل الدفعة
            $payment = Payment::create([
                'rent_installment_id' => $installment->id,
                'contract_id' => $installment->contract_id, // لسهولة الاستعلام
                'payment_date' => $data['payment_date'],
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'transaction_reference' => $data['transaction_reference'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            // 2. تحديث القسط
            $installment->amount_paid += $payment->amount;

            // 3. تحديث حالة القسط
            if ($installment->amount_paid >= ($installment->amount_due + $installment->late_fee)) {
                $installment->status = 'Paid';
            } else {
                $installment->status = 'Partially Paid';
            }

            $installment->save();

            return $payment;
        });
    }
}