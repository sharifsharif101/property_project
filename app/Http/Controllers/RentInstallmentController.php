<?php

namespace App\Http\Controllers;

use App\Models\RentInstallment;
use Illuminate\Http\Request;
use Carbon\Carbon;
class RentInstallmentController extends Controller
{
  public function index(Request $request)
    {
        // ✅ --- بداية التعديل: تحديث حالة الأقساط تلقائياً ---
        // الخطوة 1: البحث عن كل الأقساط التي حالتها "مستحق" (Due) وتاريخ استحقاقها أصبح في الماضي
        // واستخدام update() لتغيير حالتها إلى "متأخر" (Overdue) بكفاءة عالية.
        RentInstallment::where('status', 'Due')
                       ->where('due_date', '<', Carbon::now()->startOfDay())
                       ->update(['status' => 'Overdue']);
        // ✅ --- نهاية التعديل ---


        // الخطوة 2: جلب جميع الأقساط (بعد أن تم تحديث حالاتها في الخطوة السابقة)
        // مع تحميل العلاقات المرتبطة بها لتجنب مشاكل N+1.
        $installments = RentInstallment::with(['contract.tenant', 'contract.property', 'contract.unit'])
                        ->latest('due_date') //  هذه الدالة ترتب الأقساط من الأحدث إلى الأقدم حسب تاريخ الاستحقاق
                        ->get(); // يمكنك تغييرها إلى paginate(15) مثلاً إذا أردت تقسيم النتائج على صفحات

        // الخطوة 3: إرسال البيانات إلى ملف العرض (View)
        return view('installments.index', compact('installments'));
    }
 
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