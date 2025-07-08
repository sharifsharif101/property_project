<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentInstallment; // <-- استيراد النموذج

class RentInstallmentController extends Controller
{
   public function index()
    {
        // 1. جلب البيانات من قاعدة البيانات
        $installments = RentInstallment::with([
                'contract.tenant', // جلب بيانات المستأجر المرتبطة بالعقد
                'contract.property', // جلب بيانات العقار المرتبطة بالعقد
                'contract.unit' // جلب بيانات الوحدة المرتبطة بالعقد
            ])
            ->latest('due_date') // 2. ترتيبها من الأحدث إلى الأقدم بناءً على تاريخ الاستحقاق
            ->paginate(15); // 3. عرض 15 سجل فقط في كل صفحة

        // 4. إرسال البيانات إلى الـ View
        return view('installments.index', compact('installments'));
    }
}
