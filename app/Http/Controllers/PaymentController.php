<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}