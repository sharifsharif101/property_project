<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\RentInstallment;
use App\Services\PaymentService;
use InvalidArgumentException;
use Exception; // تأكد من استيراد Exception
 
class PaymentController extends Controller
{
    protected $paymentService;


    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }


public function index(Request $request)
{
    // قمنا بإضافة 'contract.property' و 'contract.unit' للاستخدام المستقبلي
    $query = Payment::with(['contract.tenant', 'contract.property', 'contract.unit', 'rentInstallment'])->latest();

    if ($request->has('installment_id')) {
        $query->where('rent_installment_id', $request->input('installment_id'));
    }
    
    // تأكد من أن 'withQueryString' موجودة لتعمل الفلاتر مع التنقل بين الصفحات
    $payments = $query->paginate(20)->withQueryString();
    
    // لم تعد هذه هناك حاجة لها إلا إذا كنت تستخدمها في الواجهة بشكل معين
    // $filterInstallmentId = $request->input('installment_id');

    return view('payments.index', compact('payments')); // لا حاجة لتمرير الفلتر الآن
}

    /**
     * ✅ دالة لإلغاء (حذف) دفعة محددة.
     */
    public function destroy(Payment $payment)
    {
        try {
            $this->paymentService->reversePayment($payment);
            return redirect()->route('payments.index')->with('success', 'تم إلغاء الدفعة بنجاح وتحديث رصيد القسط.');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء محاولة إلغاء الدفعة: ' . $e->getMessage());
        }
    }


    public function create(Request $request) 
    {
        $unpaidInstallments = RentInstallment::with('contract.tenant')
            ->whereIn('status', ['Due', 'Partially Paid', 'Overdue'])
            ->orderBy('due_date', 'asc')
            ->get();
            
        $selectedInstallmentId = $request->query('installment_id');

        return view('payments.create', compact('unpaidInstallments', 'selectedInstallmentId'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'rent_installment_id' => 'required|exists:rent_installments,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:Cash,Bank Transfer,Credit Card,Check,Online Payment',
            'transaction_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            $this->paymentService->recordPayment($validatedData);
            return redirect()->route('payments.create')->with('success', 'تم تسجيل الدفعة بنجاح!');
        
        } catch (InvalidArgumentException $e) {
            // هذا الخطأ يحدث فقط إذا كان المبلغ أكبر من المستحق
            return back()->withInput()->withErrors(['amount' => $e->getMessage()]);
        
        } catch (Exception $e) {
            // ✅✅✅ هنا المكان الصحيح لوضع كود التصحيح ✅✅✅
            // سيقوم هذا السطر بطباعة تفاصيل الخطأ الكاملة وإيقاف تنفيذ البرنامج
            dd($e);
        }
    }


    
/**
 * ✅ دالة لعرض المدفوعات مجمعة حسب العقد (عرض الأكورديون)
 */
public function accordionView()
{
    // 1. جلب كل المدفوعات مع تحميل علاقة العقد والمستأجر مسبقاً (Eager Loading)
    $payments = Payment::with(['contract.tenant'])
        ->latest('payment_date') // ترتيب الدفعات الأحدث أولاً داخل كل مجموعة
        ->get();

    // 2. تجميع المدفوعات باستخدام الرقم المرجعي للعقد
    // سنستخدم ->groupBy('contract.reference_number') إذا كانت العلاقة موجودة دائماً
    // ولكن لتجنب الأخطاء إذا كان هناك دفعة بدون عقد (نادر جداً)، سنستخدم groupBy على الـ ID
    $groupedPayments = $payments->groupBy('contract_id');
    
    // 3. تمرير البيانات المجمعة إلى الواجهة
    return view('payments.accordion', compact('groupedPayments'));
}
}