@extends('layouts.app')

@push('styles')
<style>
    /* تصميم مخصص للبطاقات والقوائم */
    .kpi-card { border-left: 4px solid; }
    .kpi-card .card-body { padding: 1.5rem; }
    .kpi-card .display-4 { font-weight: 700; }
    .list-group-item { border: none; padding: 0.85rem 1.25rem; }
</style>
@endpush

@section('content')
<div class="container-fluid mt-4">
    <!-- ================================== -->
    <!-- القسم الأول: بطاقات المؤشرات الرئيسية -->
    <!-- ================================== -->
    <div class="row">
        {{-- بطاقة العقود النشطة --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card border-primary shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">العقود النشطة</div>
                    <div class="display-4 text-gray-800">{{ $activeContractsCount }}</div>
                </div>
            </div>
        </div>

        {{-- بطاقة المبالغ المتأخرة --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card border-danger shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">إجمالي المتأخرات</div>
                    <div class="display-4 text-gray-800">{{ number_format($totalOverdue, 0) }}</div>
                    <small class="text-muted">ريال</small>
                </div>
            </div>
        </div>
        
        {{-- بطاقة الإيجارات القادمة --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card border-warning shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">إيجارات قادمة (30 يوم)</div>
                    <div class="display-4 text-gray-800">{{ number_format($upcomingRent, 0) }}</div>
                    <small class="text-muted">ريال</small>
                </div>
            </div>
        </div>
        
        {{-- بطاقة الوحدات الشاغرة --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card border-success shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">الوحدات الشاغرة</div>
                    <div class="display-4 text-gray-800">{{ $availableUnitsCount }}</div>
                </div>
            </div>
        </div>
    </div>


    <!-- ================================== -->
    <!-- القسم الثاني: نظرة عامة على الأداء -->
    <!-- ================================== -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">نظرة عامة على الإشغال</h6>
                </div>
                <div class="card-body">
                    <h4 class="small font-weight-bold">معدل الإشغال <span class="float-end">{{ number_format($occupancyRate, 1) }}%</span></h4>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $occupancyRate }}%" aria-valuenow="{{ $occupancyRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <hr>
                    <p class="text-muted">
                        هذا المؤشر يوضح نسبة الوحدات المؤجرة من إجمالي الوحدات المتاحة في النظام.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
             <div class="card shadow-sm h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">الدفعات الأخيرة</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentPayments as $payment)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ $payment->contract->tenant->first_name ?? 'مستأجر محذوف' }}</h6>
                                <small class="text-muted">{{ $payment->payment_date->diffForHumans() }}</small>
                            </div>
                            <span class="badge bg-success-subtle text-success-emphasis rounded-pill p-2">{{ number_format($payment->amount, 2) }} ريال</span>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted p-4">لا توجد دفعات مسجلة مؤخراً.</li>
                        @endforelse
                    </ul>
                </div>
             </div>
        </div>
    </div>

    <!-- ================================== -->
    <!-- القسم الثالث: إجراءات عاجلة -->
    <!-- ================================== -->
     <div class="card shadow-sm">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">تنبيهات: عقود على وشك الانتهاء (60 يوم)</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <tbody>
                        @forelse($upcomingExpiries as $contract)
                        <tr>
                            <td><i class="bi bi-file-earmark-text text-primary fs-4"></i></td>
                            <td>
                                <h6 class="mb-0"><strong>{{ $contract->tenant->first_name ?? '' }} {{ $contract->tenant->last_name ?? '' }}</strong></h6>
                                <small class="text-muted">العقار: {{ $contract->property->name ?? '' }}</small>
                            </td>
                            <td>
                                <h6 class="mb-0">تاريخ الانتهاء</h6>
                                <strong class="text-danger">{{ $contract->end_date->format('Y-m-d') }}</strong>
                            </td>
                            <td>
                                <h6 class="mb-0">رقم العقد المرجعي</h6>
                                <span class="text-muted">{{ $contract->reference_number }}</span>
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-primary">عرض العقد</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted p-4">لا توجد عقود على وشك الانتهاء.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
     </div>

</div>
@endsection