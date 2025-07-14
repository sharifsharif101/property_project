@extends('layouts.app')

@section('title', 'سجل أقساط الإيجارات')

@section('content')
    {{-- Session Messages if needed --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-md shadow-sm" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-white rounded-lg shadow-md">
        <!-- Card Header -->
        <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="text-xl font-bold text-gray-800">سجل أقساط الإيجارات</h2>
            
            <div class="flex items-center gap-2">
                <a href="{{ route('installments.accordion') }}" 
                   class="inline-flex items-center gap-2 bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-gray-700 transition-colors">
                    <i class="bi bi-collection"></i>
                    <span>عرض مجمع حسب العقد</span>
                </a>
                <a href="{{ route('payments.create') }}" 
                   class="inline-flex items-center gap-2 bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 transition-colors">
                    <i class="bi bi-plus-circle"></i>
                    <span>إضافة دفعة</span>
                </a>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <div class="overflow-x-auto">
                
                @if(isset($installments) && $installments->count() > 0)

                    <table id="installments-table" class="w-full text-xs text-right">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="p-3 font-medium text-sm">#</th>
                                <th class="p-3 font-medium text-sm">المستأجر</th>
                                <th class="p-3 font-medium text-sm">العقار / الوحدة</th>
                                <th class="p-3 font-medium text-sm">مرجع العقد</th>
                                <th class="p-3 font-medium text-sm">تاريخ الاستحقاق</th>
                                <th class="p-3 font-medium text-sm text-center">الحالة</th>
                                <th class="p-3 font-medium text-sm text-left">المستحق</th>
                                <th class="p-3 font-medium text-sm text-left">المدفوع</th>
                                <th class="p-3 font-medium text-sm text-left">المتبقي</th>
                                <th class="p-3 font-medium text-sm text-center">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($installments as $installment)
                            
                            @php
                                $rowClass = '';
                                $statusText = '';
                                $statusIcon = '';
                                $statusClass = '';

                                // التحقق إذا كان القسط متأخراً
                                if ($installment->status == 'Overdue') {
                                    
                                    // ✅ --- بداية التعديل: حساب عدد الأيام كرقم صحيح ---
                                    // نستخدم startOfDay() لضمان مقارنة الأيام الكاملة فقط وتجنب الكسور العشرية.
                                    $overdueDays = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($installment->due_date)->startOfDay());
                                    // ✅ --- نهاية التعديل ---

                                    // تحديد كلاس التمييز الأحمر للسطر
                                    $rowClass = 'bg-red-50/50 hover:bg-red-50 border-l-4 border-red-400';
                                    
                                    // تحديد معلومات الحالة للمتأخر
                                    $statusText = "متأخر منذ " . $overdueDays . " يوم";
                                    $statusIcon = 'bi-exclamation-triangle-fill';
                                    $statusClass = 'bg-red-100 text-red-800';

                                } else {
                                    // استخدام الإعدادات الافتراضية للحالات الأخرى
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

                            {{-- تطبيق الكلاس الشرطي على الصف <tr> --}}
                            <tr class="{{ $rowClass }}">
                                <td class="p-3 text-gray-700">{{ $installment->id + 10000 }}</td>
                                <td class="p-3 whitespace-nowrap">
                                    <div class="font-semibold text-sm text-gray-900">{{ $installment->contract->tenant->first_name ?? '' }} {{ $installment->contract->tenant->last_name ?? 'N/A' }}</div>
                                    <div class="text-gray-500" dir="ltr">{{ $installment->contract->tenant->phone ?? '' }}</div>
                                </td>
                                <td class="p-3 whitespace-nowrap">
                                    <div class="font-medium text-gray-800">{{ $installment->contract->property->name ?? 'N/A' }}</div>
                                    <div class="text-gray-500">وحدة: {{ $installment->contract->unit->unit_number ?? 'N/A' }}</div>
                                </td>
                                <td class="p-3">
                                    <span class="bg-gray-100 text-gray-700 font-mono px-2 py-1 rounded-md">{{ $installment->contract->reference_number ?? 'N/A' }}</span>
                                </td>
                                <td class="p-3 text-gray-800" dir="ltr">{{ \Carbon\Carbon::parse($installment->due_date)->format('Y-m-d') }}</td>
                                
                                {{-- عرض الحالة المخصصة (مع عدد أيام التأخير إذا كان متأخراً) --}}
                                <td class="p-3 text-center">
                                    <span class="inline-flex items-center gap-x-1.5 rounded-full px-2.5 py-1 font-semibold {{ $statusClass }}">
                                        <i class="bi {{ $statusIcon }}"></i><span>{{ $statusText }}</span>
                                    </span>
                                </td>
                                
                                <td class="p-3 text-left font-mono text-gray-700">{{ number_format($installment->amount_due + $installment->late_fee, 2) }}</td>
                                <td class="p-3 text-left font-mono text-green-700">{{ number_format($installment->amount_paid, 2) }}</td>
                              
                                <td class="p-3 text-left font-mono font-bold text-red-700">
                                    {{ number_format(($installment->amount_due + $installment->late_fee) - $installment->amount_paid, 2) }}
                                </td>
                                <td class="p-3 text-center">
                                    @if($installment->status == 'Paid')
                                        <span class="text-green-500" title="تم الدفع بالكامل"><i class="bi bi-check2-all text-lg"></i></span>
                                    @elseif($installment->status == 'Cancelled')
                                        <span class="text-gray-400" title="هذا القسط ملغي"><i class="bi bi-slash-circle text-lg"></i></span>
                                    @else
                                        <a href="{{ route('payments.create', ['installment_id' => $installment->id]) }}" 
                                           class="inline-flex items-center gap-1.5 font-bold py-1.5 px-3 rounded-full text-white transition-colors
                                                  {{ $installment->status == 'Partially Paid' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }}">
                                            <i class="bi bi-cash-coin"></i>
                                            <span>{{ $installment->status == 'Partially Paid' ? 'إكمال الدفع' : 'دفع الآن' }}</span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                @else 
                    <div class="text-center py-16 text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="bi bi-journal-x text-5xl text-gray-300"></i>
                            <h4 class="mt-4 font-semibold text-lg">لا توجد أقساط لعرضها حالياً.</h4>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // التحقق من وجود الجدول قبل تهيئته
            if ($('#installments-table').length) {
                $('#installments-table').DataTable({
                    "language": { "url": "https://cdn.datatables.net/plug-ins/2.0.3/i18n/ar.json" },
                    "columnDefs": [ { "orderable": false, "targets": [9] } ], // Disable sorting on actions column
                    "order": [[ 4, "desc" ]], // Sort by due date column by default
                    "responsive": true,
                });
            }
        });
    </script>
@endpush