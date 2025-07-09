{{-- This page extends the main layout --}}
@extends('layoutss.app')

{{-- Define the page title --}}
@section('title', 'لوحة التحكم الرئيسية')

{{-- Define the page content --}}
@section('contents')
  <div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">سجل أقساط الإيجارات</h1>
        <a href="{{ route('payments.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> إضافة دفعة جديدة
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            
            {{-- ✅ 1. تم حذف نموذج البحث القديم من هنا --}}

            <div class="table-responsive">
                {{-- ✅ 2. تم إضافة id="installments-table" للجدول --}}
                <table id="installments-table" class="table table-hover align-middle" style="width:100%">
                    <thead class="table-light">
                        {{-- رؤوس الأعمدة تبقى كما هي --}}
                        <tr class="text-secondary">
                            <th>#</th>
                            <th>المستأجر</th>
                            <th>العقار / الوحدة</th>
                            <th>الرقم المرجعي للعقد</th>
                            <th>تاريخ الاستحقاق</th>
                            <th class="text-center">الحالة</th>
                            <th class="text-end">المبلغ المستحق</th>
                            <th class="text-end">المبلغ المدفوع</th>
                            <th class="text-end text-danger">المبلغ المتبقي</th>
                            <th class="text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- الحلقة لعرض البيانات تبقى كما هي --}}
                        @forelse($installments as $installment)
                        <tr>
                            <td>{{ $installment->id + 10000 }}</td>
                            <td>
                                <div>{{ $installment->contract->tenant->first_name ?? 'غير متوفر' }} {{ $installment->contract->tenant->last_name ?? '' }}</div>
                                <small class="text-muted" dir="ltr">{{ $installment->contract->tenant->phone ?? '' }}</small>
                            </td>
                            <td>
                                <div>{{ $installment->contract->property->name ?? 'غير متوفر' }}</div>
                                <small class="text-muted">وحدة رقم: {{ $installment->contract->unit->unit_number ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $installment->contract->reference_number ?? 'N/A' }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($installment->due_date)->format('Y-m-d') }}</td>
                            <td class="text-center">
                                @php
                                    $statusConfig = [
                                        'Paid'           => ['class' => 'bg-success-subtle text-success-emphasis border border-success-subtle', 'text' => 'مدفوع بالكامل', 'icon' => 'bi-check-circle-fill'],
                                        'Partially Paid' => ['class' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle', 'text' => 'مدفوع جزئياً', 'icon' => 'bi-pie-chart-fill'],
                                        'Overdue'        => ['class' => 'bg-danger-subtle text-danger-emphasis border border-danger-subtle', 'text' => 'متأخر', 'icon' => 'bi-exclamation-triangle-fill'],
                                        'Due'            => ['class' => 'bg-info-subtle text-info-emphasis border border-info-subtle', 'text' => 'مستحق', 'icon' => 'bi-hourglass-split'],
                                        'Cancelled'      => ['class' => 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle', 'text' => 'ملغي', 'icon' => 'bi-x-circle-fill'],
                                    ];
                                    $config = $statusConfig[$installment->status] ?? ['class' => 'bg-light', 'text' => $installment->status, 'icon' => 'bi-question-circle'];
                                @endphp
                                <span class="badge rounded-pill {{ $config['class'] }}">
                                    <i class="bi {{ $config['icon'] }} me-1"></i>{{ $config['text'] }}
                                </span>
                            </td>
                            <td class="text-end">{{ number_format($installment->amount_due + $installment->late_fee, 2) }}</td>
                            <td class="text-end">{{ number_format($installment->amount_paid, 2) }}</td>
                            <td class="text-end fw-bold text-danger">
                                {{ number_format(($installment->amount_due + $installment->late_fee) - $installment->amount_paid, 2) }}
                            </td>
                            <td class="text-center">
                                @if($installment->status == 'Paid')
                                    <span class="text-success" title="تم الدفع بالكامل"><i class="bi bi-check2-all fs-5"></i></span>
                                @elseif($installment->status == 'Cancelled')
                                    <span class="text-secondary" title="هذا القسط ملغي"><i class="bi bi-slash-circle fs-5"></i></span>
                                @else
                                    <a href="{{ route('payments.create', ['installment_id' => $installment->id]) }}" 
                                       class="btn btn-sm {{ $installment->status == 'Partially Paid' ? 'btn-warning' : 'btn-success' }}" 
                                       title="تسجيل دفعة لهذا القسط">
                                        <i class="bi bi-cash-coin"></i>
                                        {{ $installment->status == 'Partially Paid' ? 'إكمال الدفع' : 'دفع الآن' }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-5">
                                <i class="bi bi-journal-x fs-1"></i>
                                <h4 class="mt-3">لا توجد أقساط لعرضها حالياً.</h4>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ✅ 3. تم حذف روابط Pagination الخاصة بـ Laravel --}}
            
        </div>
    </div>
</div>






@endsection

{{-- You can push page-specific scripts if needed --}}
@push('scripts')
  <script>
    // alert('This script is specific to the dashboard page!');
  </script>
@endpush