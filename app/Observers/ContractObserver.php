<?php

namespace App\Observers;

use App\Models\Contract;
use App\Services\Contract\InstallmentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Unit;

class ContractObserver
{
    public function saved(Contract $contract)
    {
        $unit = $contract->unit;
        if (!$unit) return;

        if (in_array($contract->status, ['active', 'draft'])) {
            $unit->status = 'rented';
        } elseif (in_array($contract->status, ['terminated', 'cancelled', 'expired'])) {
            $unit->status = 'available';
        }
        $unit->save();
    }
    public function deleting(Contract $contract)
    {
        // حذف جميع الأقساط المرتبطة بهذا العقد
        // هذه الطريقة هي الأسرع لأنها تنفذ استعلام حذف واحد.
        $contract->rentInstallments()->delete();

        // ملاحظة: إذا كان لديك observer على مودل RentInstallment نفسه
        // وتريد تشغيل حدث الحذف لكل قسط على حدة، استخدم الكود التالي بدلاً من السطر أعلاه:
        // $contract->rentInstallments()->each->delete();
    }

    /**
     * التعامل مع حدث "بعد" حذف العقد.
     *
     * @param  \App\Models\Contract  $contract
     * @return void
     */
    public function deleted(Contract $contract)
    {
        // الآن وبعد حذف العقد والأقساط، يمكننا تحديث حالة الوحدة
        if ($contract->unit) {
            $contract->unit->update(['status' => 'available']);
        }
    }

    public function created(Contract $contract): void
    {
        if ($contract->status === 'active') {
             (new InstallmentService())->generateForContract($contract);
        }
    }

    public function updated(Contract $contract): void
    {
        // ✅✅✅ السيناريو المضاف: تفعيل عقد كان مسودة ✅✅✅
        if ($contract->wasChanged('status') && $contract->status === 'active' && !$contract->rentInstallments()->exists()) {
            (new InstallmentService())->generateForContract($contract);
        }

        // === باقي السيناريوهات ===
        if ($contract->wasChanged('status') && in_array($contract->status, ['terminated', 'cancelled'])) {
            $contract->rentInstallments()
                     ->where('status', 'Due')
                     ->where('due_date', '>=', Carbon::today())
                     ->update(['status' => 'Cancelled']);
        }

        if ($contract->wasChanged('rent_amount')) {
            $contract->rentInstallments()
                     ->whereIn('status', ['Due', 'Overdue'])
                     ->where('due_date', '>=', Carbon::today())
                     ->update(['amount_due' => $contract->rent_amount]);
        }

        if ($contract->wasChanged('end_date') || $contract->wasChanged('rent_type')) {
            DB::transaction(function () use ($contract) {
                $contract->rentInstallments()
                         ->whereIn('status', ['Due', 'Overdue'])
                         ->where('due_date', '>=', Carbon::today())
                         ->delete();

                (new InstallmentService())->regenerateForContract($contract, Carbon::today());
            });
        }
    }
}