<?php

namespace App\Observers;

use App\Models\Contract;

 use App\Services\Contract\InstallmentService; // استيراد الـ Service

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
        // إذا تم تفعيل العقد بعد إنشائه
        if ($contract->wasChanged('status') && $contract->status === 'active') {
             // تأكد من عدم وجود أقساط سابقة قبل إنشائها مجدداً
            if ($contract->rentInstallments()->count() === 0) {
                (new InstallmentService())->generateForContract($contract);
            }
        }
    }

}
