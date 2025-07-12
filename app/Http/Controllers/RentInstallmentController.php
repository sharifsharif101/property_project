<?php

namespace App\Http\Controllers;

use App\Models\RentInstallment;
use Illuminate\Http\Request;

class RentInstallmentController extends Controller
{
    // دالة index الحالية لديك
    public function index(Request $request)
    {
        // ... الكود الحالي لدالة index ...
        // يجب أن يجلب الأقساط ويمررها إلى installments.index
        $installments = RentInstallment::with(['contract.tenant', 'contract.property', 'contract.unit'])
                        ->latest('due_date')
                        ->get(); // أو paginate
        
        return view('installments.index', compact('installments'));
    }

    /**
     * ✅✅✅ الدالة الجديدة لعرض الأقساط المجمعة ✅✅✅
     */
    public function accordionView()
    {
        // جلب الأقساط مع تحميل العلاقات الضرورية مسبقاً
        $installments = RentInstallment::with(['contract.tenant'])
            ->orderBy('due_date', 'asc') // ترتيب الأقساط حسب تاريخ الاستحقاق داخل كل عقد
            ->get();

        // تجميع الأقساط حسب رقم العقد
        $groupedInstallments = $installments->groupBy('contract_id');

        // تمرير البيانات المجمعة إلى الواجهة الجديدة
        return view('installments.accordion', compact('groupedInstallments'));
    }
}