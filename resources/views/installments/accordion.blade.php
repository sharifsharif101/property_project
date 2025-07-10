@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">

    <!-- العنوان والأزرار -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-primary">
            <i class="bi bi-list-check me-2"></i> سجل أقساط الإيجارات (عرض قائمة)
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('installments.index') }}" class="btn btn-outline-dark" title="عرض كجدول">
                <i class="bi bi-table"></i>
            </a>
            <a href="{{ route('payments.create') }}" class="btn btn-primary shadow-sm px-4">
                <i class="bi bi-plus-circle me-1"></i> دفعة جديدة
            </a>
        </div>
    </div>

    <!-- قائمة الأقساط -->
    <div class="accordion" id="installmentsAccordion">
        @forelse($installments as $installment)
            @php
                $contract = $installment->contract;
                $tenant = $contract->tenant;
                $property = $contract->property;
                $unit = $contract->unit;

                $due = $installment->amount_due + $installment->late_fee;
                $paid = $installment->amount_paid;
                $remaining = $due - $paid;

                $statusConfig = [
                    'Paid'           => ['class' => 'bg-success-subtle text-success-emphasis border border-success-subtle', 'text' => 'مدفوع'],
                    'Partially Paid' => ['class' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle', 'text' => 'مدفوع جزئياً'],
                    'Overdue'        => ['class' => 'bg-danger-subtle text-danger-emphasis border border-danger-subtle', 'text' => 'متأخر'],
                    'Due'            => ['class' => 'bg-info-subtle text-info-emphasis border border-info-subtle', 'text' => 'مستحق'],
                    'Cancelled'      => ['class' => 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle', 'text' => 'ملغي'],
                ];
                $status = $statusConfig[$installment->status] ?? ['class' => 'bg-light', 'text' => $installment->status];
            @endphp

            <div class="accordion-item mb-3 shadow-sm border-0">
                <h2 class="accordion-header" id="heading-{{ $installment->id }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{ $installment->id }}" aria-expanded="false"
                            aria-controls="collapse-{{ $installment->id }}">
                        <div class="w-100 d-flex justify-content-between align-items-center pe-3">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $tenant->first_name }} {{ $tenant->last_name }}</h6>
                                <small class="text-muted">استحقاق: {{ \Carbon\Carbon::parse($installment->due_date)->format('Y-m-d') }}</small>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge rounded-pill px-3 py-2 {{ $status['class'] }}">{{ $status['text'] }}</span>
                                <span class="fw-bold text-danger">{{ number_format($remaining, 2) }} <small>ريال</small></span>
                            </div>
                        </div>
                    </button>
                </h2>

                <div id="collapse-{{ $installment->id }}" class="accordion-collapse collapse"
                     aria-labelledby="heading-{{ $installment->id }}" data-bs-parent="#installmentsAccordion">
                    <div class="accordion-body bg-light-subtle">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <ul class="list-group list-group-flush bg-white rounded shadow-sm">
                                    <li class="list-group-item d-flex justify-content-between"><strong>العقار:</strong> {{ $property->name ?? 'N/A' }}</li>
                                    <li class="list-group-item d-flex justify-content-between"><strong>الوحدة:</strong> {{ $unit->unit_number ?? 'N/A' }}</li>
                                    <li class="list-group-item d-flex justify-content-between"><strong>رقم العقد:</strong> {{ $contract->reference_number ?? 'N/A' }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6 mb-3">
                                <ul class="list-group list-group-flush bg-white rounded shadow-sm">
                                    <li class="list-group-item d-flex justify-content-between"><strong>المستحق:</strong> {{ number_format($installment->amount_due, 2) }} ريال</li>
                                    <li class="list-group-item d-flex justify-content-between"><strong>رسوم التأخير:</strong> {{ number_format($installment->late_fee, 2) }} ريال</li>
                                    <li class="list-group-item d-flex justify-content-between"><strong>المدفوع:</strong> {{ number_format($paid, 2) }} ريال</li>
                                </ul>
                            </div>
                        </div>

                        @if(!in_array($installment->status, ['Paid', 'Cancelled']))
                            <div class="d-flex justify-content-end mt-3">
                                <a href="{{ route('payments.create', ['installment_id' => $installment->id]) }}"
                                   class="btn {{ $installment->status == 'Partially Paid' ? 'btn-warning' : 'btn-success' }} rounded-pill px-4 shadow-sm">
                                    <i class="bi bi-cash-coin me-1"></i>
                                    {{ $installment->status == 'Partially Paid' ? 'إكمال الدفع' : 'دفع الآن' }}
                                </a>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">
                <i class="bi bi-journal-x fs-1"></i>
                <h4 class="mt-3">لا توجد أقساط لعرضها حالياً.</h4>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($installments->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $installments->links() }}
        </div>
    @endif

</div>
@endsection
