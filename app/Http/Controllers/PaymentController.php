<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RentInstallment;
use App\Services\PaymentService;
 
class PaymentController extends Controller
{
     protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * عرض نموذج إضافة دفعة جديدة
     */
   public function create(Request $request) 
    {
        // جلب جميع الأقساط التي لم تُدفع بالكامل
        // يفضل تحسين هذا الاستعلام في تطبيق حقيقي (مثل إضافة pagination)
        $unpaidInstallments = RentInstallment::with('contract.tenant')
            ->whereIn('status', ['Due', 'Partially Paid', 'Overdue'])
            ->orderBy('due_date', 'asc')
            ->get();
    $selectedInstallmentId = $request->query('installment_id');

    return view('payments.create', compact('unpaidInstallments', 'selectedInstallmentId'));
    }

    /**
     * تخزين دفعة جديدة في قاعدة البيانات
     */
    public function store(Request $request)
    {
        // 1. التحقق من صحة المدخلات
        $validatedData = $request->validate([
            'rent_installment_id' => 'required|exists:rent_installments,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:Cash,Bank Transfer,Credit Card,Check,Online Payment',
            'transaction_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // 2. استدعاء الـ Service لمعالجة الدفعة
        try {
            $this->paymentService->recordPayment($validatedData);
        } catch (\InvalidArgumentException $e) {
            // في حالة كان المبلغ المدفوع أكبر من المستحق
            return back()->withInput()->withErrors(['amount' => $e->getMessage()]);
        } catch (\Exception $e) {
            // لأي خطأ آخر غير متوقع
            return back()->with('error', 'حدث خطأ غير متوقع أثناء تسجيل الدفعة.');
        }

        // 3. إعادة التوجيه مع رسالة نجاح
        return redirect()->route('payments.create')->with('success', 'تم تسجيل الدفعة بنجاح!');
    }
}
