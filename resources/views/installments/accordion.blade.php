@extends('layouts.app')

@section('title', 'عرض الأقساط المجمع')

@section('content')

    <div class="bg-white rounded-lg shadow-md">
        <!-- رأس الكارد -->
        <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">عرض الأقساط المجمع حسب العقد</h2>
                <p class="text-sm text-gray-500 mt-1">عرض تفصيلي لحالة سداد الأقساط لكل عقد على حدة.</p>
            </div>
            <a href="{{ route('installments.index') }}" 
               class="inline-flex items-center gap-2 bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-gray-700 transition-colors">
                <i class="bi bi-list-ul"></i>
                <span>عرض كقائمة</span>
            </a>
        </div>

        <!-- محتوى الكارد -->
        <div class="p-6">
            @if($groupedInstallments->isEmpty())
                <div class="text-center py-16 text-gray-500">
                    <i class="bi bi-journal-x text-5xl text-gray-300"></i>
                    <h4 class="mt-4 font-semibold text-lg">لا توجد أي أقساط لعرضها.</h4>
                </div>
            @else
                <div class="space-y-4" x-data="{ openContract: null }">
                    @foreach ($groupedInstallments as $contractId => $installmentsInGroup)
                        @php
                            $contract = $installmentsInGroup->first()->contract;
                            $totalDue = $installmentsInGroup->sum(fn($i) => $i->amount_due + $i->late_fee);
                            $totalPaid = $installmentsInGroup->sum('amount_paid');
                            $remainingAmount = $totalDue - $totalPaid;
                            $paidPercentage = ($totalDue > 0) ? ($totalPaid / $totalDue) * 100 : 0;
                            $installmentsCount = $installmentsInGroup->count();
                        @endphp
                        
                        <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                            {{-- رأس الأكورديون القابل للضغط --}}
                            <button @click="openContract = openContract === {{ $contractId }} ? null : {{ $contractId }}" class="w-full p-4 text-right bg-gray-50 hover:bg-gray-100 flex justify-between items-center transition">
                                <div class="flex items-center gap-4">
                                    <span class="flex items-center justify-center w-12 h-12 bg-blue-100 text-blue-600 rounded-lg">
                                        <i class="bi bi-file-earmark-text text-2xl"></i>
                                    </span>
                                    <div>
                                        <p class="font-bold text-gray-800 text-base">عقد: <span class="font-mono">{{ $contract->reference_number ?? 'N/A' }}</span></p>
                                        <p class="text-sm text-gray-600">
                                            المستأجر: {{ $contract->tenant->first_name ?? '' }} {{ $contract->tenant->last_name ?? '' }}
                                        </p>
                                    </div>
                                </div>
                                <i class="bi text-xl transition-transform" :class="openContract === {{ $contractId }} ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                            </button>

                            <div x-show="openContract === {{ $contractId }}" x-collapse class="bg-white">

                                {{-- لوحة الإحصائيات وشريط التقدم --}}
                                <div class="px-4 py-4 bg-gray-50/50 border-t border-b border-gray-200">
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                                        <div>
                                            <p class="text-sm text-gray-500 mb-1">إجمالي المستحق</p>
                                            <p class="font-bold text-gray-800 font-mono text-base">{{ number_format($totalDue, 2) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 mb-1">إجمالي المدفوع</p>
                                            <p class="font-bold text-green-600 font-mono text-base">{{ number_format($totalPaid, 2) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 mb-1">المبلغ المتبقي</p>
                                            <p class="font-bold text-red-600 font-mono text-base">{{ number_format($remainingAmount, 2) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 mb-1">عدد الأقساط</p>
                                            <p class="font-bold text-blue-600 font-mono text-base">{{ $installmentsCount }}</p>
                                        </div>
                                    </div>
                                    {{-- شريط التقدم --}}
                                    <div class="mt-4">
                                        <div class="w-full bg-gray-200 rounded-full h-3">
                                            <div class="bg-green-500 h-3 rounded-full" style="width: {{ $paidPercentage }}%"></div>
                                        </div>
                                        <p class="text-sm text-center text-gray-600 mt-1.5">تم سداد {{ round($paidPercentage) }}% من إجمالي العقد</p>
                                    </div>
                                </div>

                                {{-- ✅✅✅ بداية التعديل: جدول الأقساط بالمنطق المتقدم ✅✅✅ --}}
                                <div class="p-4 overflow-x-auto">
                                    <table class="w-full text-sm text-right">
                                        <thead class="text-gray-600">
                                            <tr>
                                                <th class="pb-2 font-medium">تاريخ الاستحقاق</th>
                                                <th class="pb-2 font-medium text-center">الحالة</th>
                                                <th class="pb-2 font-medium text-left">المستحق</th>
                                                <th class="pb-2 font-medium text-left">المدفوع</th>
                                                <th class="pb-2 font-medium text-left">المتبقي</th>
                                                <th class="pb-2 font-medium text-center">إجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($installmentsInGroup as $installment)

                                            @php
                                                $rowClass = '';
                                                $statusText = '';
                                                $statusIcon = '';
                                                $statusClass = '';

                                                if ($installment->status == 'Overdue') {
                                                    // حساب أيام التأخير كرقم صحيح وموجب
                                                    $overdueDays = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($installment->due_date)->startOfDay());
                                                    
                                                    // تطبيق كلاس خفيف على السطر بالكامل
                                                    $rowClass = 'bg-red-50/50';
                                                    
                                                    // إعداد نص وأيقونة الحالة للمتأخر
                                                    $statusText = "متأخر منذ " . $overdueDays . " يوم";
                                                    $statusIcon = 'bi-exclamation-triangle-fill';
                                                    $statusClass = 'bg-red-100 text-red-800';

                                                } else {
                                                    // الإعدادات الافتراضية لباقي الحالات
                                                    $statusConfig = [
                                                        'Paid'           => ['class' => 'bg-green-100 text-green-800', 'text' => 'مدفوع بالكامل', 'icon' => 'bi-check-circle-fill'],
                                                        'Partially Paid' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'مدفوع جزئياً', 'icon' => 'bi-pie-chart-fill'],
                                                        'Due'            => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'مستحق', 'icon' => 'bi-hourglass-split'],
                                                        'Cancelled'      => ['class' => 'bg-gray-200 text-gray-600', 'text' => 'ملغي', 'icon' => 'bi-x-circle-fill'],
                                                    ];
                                                    $config = $statusConfig[$installment->status] ?? ['class' => 'bg-gray-100', 'text' => $installment->status, 'icon' => 'bi-question-circle'];
                                                    
                                                    $statusText = $config['text'];
                                                    $statusIcon = $config['icon'];
                                                    $statusClass = $config['class'];
                                                }
                                            @endphp

                                            <tr class="border-b border-gray-100 last:border-b-0 {{ $rowClass }}">
                                                <td class="py-3 text-gray-800" dir="ltr">{{ \Carbon\Carbon::parse($installment->due_date)->format('Y-m-d') }}</td>
                                                <td class="py-3 text-center">
                                                    {{-- عرض الحالة المتقدم مع الأيقونة --}}
                                                    <span class="inline-flex items-center gap-x-1.5 rounded-full px-2.5 py-1 font-semibold text-xs {{ $statusClass }}">
                                                        <i class="bi {{ $statusIcon }}"></i><span>{{ $statusText }}</span>
                                                    </span>
                                                </td>
                                                <td class="py-3 text-left font-mono text-gray-700">{{ number_format($installment->amount_due + $installment->late_fee, 2) }}</td>
                                                <td class="py-3 text-left font-mono text-green-700">{{ number_format($installment->amount_paid, 2) }}</td>
                                                <td class="py-3 text-left font-mono font-bold text-red-700">{{ number_format(($installment->amount_due + $installment->late_fee) - $installment->amount_paid, 2) }}</td>
                                                <td class="py-3 text-center">
                                                    @if(!in_array($installment->status, ['Paid', 'Cancelled']))
                                                        <a href="{{ route('payments.create', ['installment_id' => $installment->id]) }}" class="text-green-600 hover:text-green-800 text-base" title="إضافة دفعة"><i class="bi bi-cash-coin"></i></a>
                                                    @else
                                                        <span class="text-gray-400 text-base" title="الحالة مكتملة"><i class="bi bi-check-circle"></i></span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{-- نهاية التعديل --}}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection