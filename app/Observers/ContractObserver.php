<?php

namespace App\Observers;

use App\Models\Contract;

 
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
}
