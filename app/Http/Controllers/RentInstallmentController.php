<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentInstallment; // <-- استيراد النموذج
 

 
 
class RentInstallmentController extends Controller
{
 
    public function index(Request $request)
{
    $query = RentInstallment::query();

    if ($request->has('search_ref') && $request->filled('search_ref')) {
        
        $searchTerm = $request->input('search_ref');

        // ▼▼▼ هذا هو الجزء الذي تم تحسينه ▼▼▼
        $query->where(function ($q) use ($searchTerm) {
            // نبحث في الرقم المرجعي للعقد
            $q->whereHas('contract', function ($subQuery) use ($searchTerm) {
                $subQuery->where('reference_number', 'like', '%' . $searchTerm . '%');
            })
            // أو نبحث في اسم المستأجر
            ->orWhereHas('contract.tenant', function ($subQuery) use ($searchTerm) {
                $subQuery->where('first_name', 'like', '%' . $searchTerm . '%')
                         ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                         ->orWhere('phone', 'like', '%' . $searchTerm . '%'); // يمكن إضافة رقم الهاتف أيضاً
            });
        });
        // ▲▲▲ نهاية الجزء المحسّن ▲▲▲
    }

    $installments = $query->with([
            'contract.tenant', 
            'contract.property',
            'contract.unit'
        ])
        ->latest('due_date')
        ->paginate(15)
        ->appends($request->query()); 

    return view('installments.index', compact('installments'));
}

 public function accordionView()
    {
        // نستخدم نفس الاستعلام بالضبط
        $installments = RentInstallment::with(['contract.tenant', 'contract.property', 'contract.unit'])
            ->latest('due_date')
            ->paginate(15);

        // لكننا نرسل البيانات إلى ملف view جديد
        return view('installments.accordion', compact('installments'));
    }

}
