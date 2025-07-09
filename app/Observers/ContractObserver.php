<?php

namespace App\Observers;

use App\Models\Contract;

 use App\Services\Contract\InstallmentService; // استيراد الـ Service
 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Unit;

class ContractObserver
{
     public function saved(Contract $contract)
    {
        // تحديث حالة الوحدة حسب حالة العقد

        $unit = $contract->unit;

        if (!$unit) {
            return; // إذا لم تكن هناك وحدة مرتبطة، لا تفعل شيء
        }

        // إذا كانت حالة العقد نشطة: اجعل الوحدة مؤجرة
if (in_array($contract->status, ['active', 'draft'])) {
            $unit->status = 'rented';
            $unit->save();
        }

        // إذا كانت حالة العقد منتهية أو ملغية: اجعل الوحدة متاحة
        if (in_array($contract->status, ['terminated', 'cancelled'])) {
            $unit->status = 'available';
            $unit->save();
        }
    }

    public function deleted(Contract $contract)
    {
        // إذا تم حذف العقد: اجعل الوحدة متاحة
        $unit = $contract->unit;

        if ($unit) {
            $unit->status = 'available';
            $unit->save();
        }
    }

 // سيتم تنفيذ هذه الدالة "بعد" إنشاء العقد مباشرة
    public function created(Contract $contract): void
    {
        // تحقق من أن حالة العقد "Active" أو ما شابه قبل إنشاء الأقساط
        if ($contract->status === 'active') { // افترض أن لديك حقل status في جدول contracts
             (new InstallmentService())->generateForContract($contract);
        }
    }

    // يمكنك أيضاً التعامل مع تحديث العقد هنا
 public function updated(Contract $contract): void
{
    // === السيناريو 1: التعامل مع إنهاء أو إلغاء العقد ===
    if ($contract->wasChanged('status') && in_array($contract->status, ['terminated', 'cancelled'])) {
        $contract->rentInstallments()
                 ->where('status', 'Due')
                 ->where('due_date', '>=', Carbon::today())
                 ->update(['status' => 'Cancelled']);
    }

    // === السيناريو 2: التعامل مع تغيير قيمة الإيجار ===
    if ($contract->wasChanged('rent_amount')) {
        $contract->rentInstallments()
                 ->whereIn('status', ['Due', 'Overdue'])
                 // لا نعدل أقساط الماضي حتى لو كانت متأخرة، السعر يتغير للمستقبل فقط
                 ->where('due_date', '>=', Carbon::today())
                 ->update(['amount_due' => $contract->rent_amount]);
    }

    // === السيناريو 3: التعامل مع تغيير هيكل العقد (تاريخ النهاية أو نوع الإيجار) ===
    if ($contract->wasChanged('end_date') || $contract->wasChanged('rent_type')) {
        DB::transaction(function () use ($contract) {
            // حذف الأقساط المستقبلية التي لم تُدفع بعد
            $contract->rentInstallments()
                     ->whereIn('status', ['Due', 'Overdue'])
                     ->where('due_date', '>=', Carbon::today())
                     ->delete();

            // إعادة توليد الأقساط من اليوم فصاعداً
            (new InstallmentService())->regenerateForContract($contract, Carbon::today());
        });
    }
}

}
