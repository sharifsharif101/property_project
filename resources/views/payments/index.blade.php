@extends('layouts.app')

@section('title', 'سجل المدفوعات')

@section('content')
    {{-- رسائل النجاح أو الخطأ --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-md shadow-sm" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-md shadow-sm" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif
 
    <!-- الكارد الرئيسي -->
    <div class="bg-white rounded-lg shadow-md">
        <!-- رأس الكارد -->
  <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
    
    {{-- العنصر الأول: العنوان (سيتم دفعه إلى اليمين تلقائياً) --}}
    <h2 class="text-xl font-bold text-gray-800">سجل المدفوعات</h2>
    
    {{-- حاوية لتجميع الأزرار معاً (سيتم دفعها إلى اليسار تلقائياً) --}}
    <div class="flex items-center gap-3">
        
        {{-- الزر الأول: عرض مجمع --}}
        <a href="{{ route('payments.accordion') }}" {{-- من الأفضل استخدام دالة route() --}}
           class="inline-flex items-center gap-2 bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-gray-700 transition-colors">
            <i class="bi bi-collection"></i>
            <span>عرض مجمع</span>
        </a>

        {{-- الزر الثاني: إضافة دفعة جديدة --}}
        <a href="{{ route('payments.create') }}" {{-- من الأفضل استخدام دالة route() --}}
           class="inline-flex items-center gap-2 bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 transition-colors">
            <i class="bi bi-plus-circle"></i>
            <span>إضافة دفعة جديدة</span>
        </a>

    </div>

</div>
        <!-- محتوى الكارد -->
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="p-3 font-medium">#</th>
                            <th class="p-3 font-medium">المستأجر</th>
                            <th class="p-3 font-medium">مرجع القسط</th>
                            <th class="p-3 font-medium">تاريخ الدفع</th>
                            <th class="p-3 font-medium text-left">المبلغ</th>
                            <th class="p-3 font-medium">طريقة الدفع</th>
                            <th class="p-3 font-medium text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 text-gray-700 font-mono">{{ $payment->id }}</td>
                            <td class="p-3 whitespace-nowrap">
                                {{-- تأكد من وجود العلاقات لتجنب الأخطاء --}}
                                <div class="font-semibold text-gray-900">{{ $payment->contract->tenant->first_name ?? '' }} {{ $payment->contract->tenant->last_name ?? 'N/A' }}</div>
                                <div class="text-gray-500" dir="ltr">{{ $payment->contract->tenant->phone ?? '' }}</div>
                            </td>
                            <td class="p-3">
                                <a href="{{ route('installments.index', ['installment_id' => $payment->rent_installment_id]) }}" class="text-blue-600 hover:underline">
                                    قسط #{{ $payment->rent_installment_id + 10000 }}
                                </a>
                            </td>
                            <td class="p-3 text-gray-800" dir="ltr">{{ $payment->payment_date->format('Y-m-d') }}</td>
                            <td class="p-3 text-left font-mono text-green-700 font-semibold">{{ number_format($payment->amount, 2) }}</td>
                            <td class="p-3 text-gray-600">{{ $payment->payment_method }}</td>
                            
                            {{-- ✅✅✅ هذا هو الجزء الذي تم تعديله ✅✅✅ --}}
                            <td class="p-3 text-center">
                                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من رغبتك في إلغاء هذه الدفعة؟ سيتم خصم المبلغ من القسط المرتبط.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-x-2 bg-red-500 text-white font-semibold py-1.5 px-3 rounded-md shadow-sm hover:bg-red-600 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                            title="إلغاء الدفعة">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                        <span>إلغاء</span>
                                    </button>
                                </form>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-16 text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="bi bi-wallet2 text-5xl text-gray-300"></i>
                                    <h4 class="mt-4 font-semibold text-lg">لا توجد أي مدفوعات مسجلة بعد.</h4>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- روابط التنقل بين الصفحات -->
            <div class="mt-6">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
@endsection