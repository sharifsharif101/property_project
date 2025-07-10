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
            <a href="{{ route('payments.create') }}" 
               class="inline-flex items-center gap-2 bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 transition-colors">
                <i class="bi bi-plus-circle"></i>
                <span>إضافة دفعة جديدة</span>
            </a>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <div class="overflow-x-auto">
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
                        @forelse($installments as $installment)
                        <tr class="hover:bg-gray-50">
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
                            <td class="p-3 text-center">
                                @php
                                    $statusConfig = [
                                        'Paid'           => ['class' => 'bg-green-100 text-green-800', 'text' => 'مدفوع بالكامل', 'icon' => 'bi-check-circle-fill'],
                                        'Partially Paid' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'مدفوع جزئياً', 'icon' => 'bi-pie-chart-fill'],
                                        'Overdue'        => ['class' => 'bg-red-100 text-red-800', 'text' => 'متأخر', 'icon' => 'bi-exclamation-triangle-fill'],
                                        'Due'            => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'مستحق', 'icon' => 'bi-hourglass-split'],
                                        'Cancelled'      => ['class' => 'bg-gray-200 text-gray-600', 'text' => 'ملغي', 'icon' => 'bi-x-circle-fill'],
                                    ];
                                    $config = $statusConfig[$installment->status] ?? ['class' => 'bg-gray-100', 'text' => $installment->status, 'icon' => 'bi-question-circle'];
                                @endphp
                                <span class="inline-flex items-center gap-x-1.5 rounded-full px-2.5 py-1 font-semibold {{ $config['class'] }}">
                                    <i class="bi {{ $config['icon'] }}"></i><span>{{ $config['text'] }}</span>
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
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-16 text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="bi bi-journal-x text-5xl text-gray-300"></i>
                                    <h4 class="mt-4 font-semibold text-lg">لا توجد أقساط لعرضها حالياً.</h4>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#installments-table').DataTable({
                "language": { "url": "https://cdn.datatables.net/plug-ins/2.0.3/i18n/ar.json" },
                "columnDefs": [ { "orderable": false, "targets": [9] } ], // Disable sorting on actions column
                "order": [[ 4, "desc" ]], // Sort by due date column by default
                "responsive": true,
            });
        });
    </script>
@endpush