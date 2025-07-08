 @extends('layouts.app') 

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">سجل الأقساط</h1>
        {{-- رابط لصفحة إضافة دفعة التي أنشأناها سابقاً --}}
        <a href="{{ route('payments.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle"></i> إضافة دفعة
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>المستأجر</th>
                            <th>العقار / الوحدة</th>
                            <th>تاريخ الاستحقاق</th>
                            <th class="text-center">الحالة</th>
                            <th>المستحق</th>
                            <th>المدفوع</th>
                            <th class="text-danger">المتبقي</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($installments as $installment)
                        <tr>
                            <td>{{ $installment->id }}</td>
                            <td>
                                {{-- تأكد من وجود علاقة tenant في نموذج Contract --}}
                                <div>{{ $installment->contract->tenant->first_name ?? 'غير متوفر' }} {{ $installment->contract->tenant->last_name ?? '' }}</div>
                                <small class="text-muted">{{ $installment->contract->tenant->phone ?? '' }}</small>
                            </td>
                            <td>
                                {{-- تأكد من وجود علاقة property و unit في نموذج Contract --}}
                                <div>{{ $installment->contract->property->name ?? 'غير متوفر' }}</div>
                                <small class="text-muted">وحدة: {{ $installment->contract->unit->unit_number ?? 'N/A' }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($installment->due_date)->format('Y-m-d') }}</td>
                            <td class="text-center">
                                @php
                                    $statusConfig = [
                                        'Paid'           => ['class' => 'bg-success', 'text' => 'مدفوع'],
                                        'Partially Paid' => ['class' => 'bg-warning text-dark', 'text' => 'مدفوع جزئياً'],
                                        'Overdue'        => ['class' => 'bg-danger', 'text' => 'متأخر'],
                                        'Due'            => ['class' => 'bg-info text-dark', 'text' => 'مستحق'],
                                        'Cancelled'      => ['class' => 'bg-secondary', 'text' => 'ملغي'],
                                    ];
                                    $config = $statusConfig[$installment->status] ?? ['class' => 'bg-light', 'text' => $installment->status];
                                @endphp
                                <span class="badge rounded-pill {{ $config['class'] }}">{{ $config['text'] }}</span>
                            </td>
                            <td>{{ number_format($installment->amount_due + $installment->late_fee, 2) }}</td>
                            <td>{{ number_format($installment->amount_paid, 2) }}</td>
                            <td class="fw-bold text-danger">
                                {{ number_format(($installment->amount_due + $installment->late_fee) - $installment->amount_paid, 2) }}
                            </td>
                            <td>
                                <a href="{{ route('payments.create') }}?installment_id={{ $installment->id }}" class="btn btn-sm btn-outline-success" title="تسجيل دفعة لهذا القسط">
                                    دفع
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <h4>لا توجد أقساط لعرضها حالياً.</h4>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- عرض أزرار التنقل بين الصفحات (Pagination) --}}
            @if ($installments->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $installments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection