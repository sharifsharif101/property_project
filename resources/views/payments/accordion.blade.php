@extends('layouts.app')

@section('title', 'عرض المدفوعات المجمع')

@section('content')
    <!-- الكارد الرئيسي -->
    <div class="bg-white rounded-lg shadow-md">
        <!-- رأس الكارد -->
        <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">عرض المدفوعات المجمع حسب العقد</h2>
                <p class="text-sm text-gray-500 mt-1">اضغط على عقد لعرض تفاصيل الدفعات الخاصة به.</p>
            </div>
            <a href="{{ route('payments.index') }}" 
               class="inline-flex items-center gap-2 bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-gray-700 transition-colors">
                <i class="bi bi-list-ul"></i>
                <span>عرض كقائمة</span>
            </a>
        </div>

        <!-- محتوى الكارد -->
        <div class="p-6">
            @if($groupedPayments->isEmpty())
                <div class="text-center py-16 text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="bi bi-wallet2 text-5xl text-gray-300"></i>
                        <h4 class="mt-4 font-semibold text-lg">لا توجد أي مدفوعات لعرضها.</h4>
                    </div>
                </div>
            @else
                <div class="space-y-3" x-data="{ openContract: null }">
                    @foreach ($groupedPayments as $contractId => $payments)
                        @php
                            // احصل على بيانات العقد والمستأجر من أول دفعة في المجموعة
                            $contract = $payments->first()->contract;
                            $totalPaid = $payments->sum('amount');
                        @endphp
                        
                        {{-- حاوية كل عقد --}}
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            {{-- رأس الأكورديون القابل للضغط --}}
                            <button @click="openContract = openContract === {{ $contractId }} ? null : {{ $contractId }}" class="w-full p-4 text-right bg-gray-50 hover:bg-gray-100 flex justify-between items-center transition">
                                <div class="flex items-center gap-4">
                                    <i class="bi bi-file-earmark-text text-xl text-gray-500"></i>
                                    <div>
                                        <p class="font-bold text-gray-800">عقد: <span class="font-mono">{{ $contract->reference_number ?? 'N/A' }}</span></p>
                                        <p class="text-sm text-gray-600">
                                            المستأجر: {{ $contract->tenant->first_name ?? '' }} {{ $contract->tenant->last_name ?? '' }} | إجمالي المدفوعات: <span class="font-mono font-semibold text-green-700">{{ number_format($totalPaid, 2) }}</span>
                                        </p>
                                    </div>
                                </div>
                                <i class="bi transition-transform" :class="openContract === {{ $contractId }} ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                            </button>
                            
                            {{-- محتوى الأكورديون (الجدول المخفي) --}}
                            <div x-show="openContract === {{ $contractId }}" x-collapse>
                                <div class="p-4 border-t border-gray-200 bg-white">
                                    <table class="w-full text-sm text-right">
                                        <thead class="text-gray-500">
                                            <tr>
                                                <th class="pb-2 font-medium">تاريخ الدفع</th>
                                                <th class="pb-2 font-medium text-left">المبلغ</th>
                                                <th class="pb-2 font-medium">طريقة الدفع</th>
                                                <th class="pb-2 font-medium text-center">الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach ($payments as $payment)
                                                <tr>
                                                    <td class="py-2 text-gray-700" dir="ltr">{{ $payment->payment_date->format('Y-m-d') }}</td>
                                                    <td class="py-2 text-left font-mono text-green-700">{{ number_format($payment->amount, 2) }}</td>
                                                    <td class="py-2 text-gray-600">{{ $payment->payment_method }}</td>
                                                    <td class="py-2 text-center">
                                                        <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من إلغاء هذه الدفعة؟')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 hover:text-red-700" title="إلغاء الدفعة">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection