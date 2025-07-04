@extends('layouts.app')

@php
    use Carbon\Carbon;
@endphp

@section('content')
<style>
    .table-containerr {
        width:100%;
        margin: auto;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        min-width: 1000px;
    }

    th, td {
        border: 1px solid #ccc;
        padding: 14px;
        text-align: center;
        white-space: nowrap;
    }

    th {
        background-color: #4a90e2;
        color: white;
    }

    tr:hover {
        background-color: #f0f8ff;
    }

    .status {
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: bold;
        font-size: 14px;
    }

    .active { background-color: #d4edda; color: #155724; }
    .terminated { background-color: #e2e3e5; color: #383d41; }
    .cancelled { background-color: #f8d7da; color: #721c24; }
    .draft { background-color: #fff3cd; color: #856404; }

    .action-btns a, .action-btns form {
        display: inline-block;
        margin: 0 4px;
    }

    .action-btns a, .action-btns button {
        padding: 4px 8px;
        font-size: 14px;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-show { background-color: #e0f0ff; color: #0056b3; }
    .btn-edit { background-color: #fff3cd; color: #856404; }
    .btn-delete { background-color: #f8d7da; color: #721c24; }

    /* أنماط للإشعارات */
    .alert {
        padding: 6px 10px;
        border-radius: 6px;
        margin-top: 6px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
    }

    .alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
    }

    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border: 1px solid #bee5eb;
    }

    .text-danger {
        color: #dc3545;
    }

    .text-muted {
        color: #6c757d;
    }

    .expired-row {
        background-color: #f8f9fa;
        opacity: 0.8;
    }

    .expiring-row {
        background-color: #fff3cd;
    }

    .not-started-row {
        background-color: #e7f3ff;
    }
</style>

<h2 style="font-size: 28px; margin: 30px 0; text-align: center;">قائمة العقود ({{ $contracts->count() }})</h2>

<div class="table-containerr">
    <table>
        <thead>
            <tr>
                <th>المستأجر</th>
                <th>العقار</th>
                <th>الوحدة</th>
                <th>البداية</th>
                <th>النهاية</th>
                <th>الحالة</th>
                <th>المرجع</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contracts as $contract)
                @php
                    // التحقق من صحة التواريخ أولاً
                    $hasValidDates = $contract->start_date && $contract->end_date;
                    
                    if ($hasValidDates) {
                        try {
                            $startDate = Carbon::parse($contract->start_date);
                            $endDate = Carbon::parse($contract->end_date);
                            $today = Carbon::today();
                            
                            // حساب الأيام المتبقية
                            $daysLeft = $today->diffInDays($endDate, false);
                            
                            // تحديد حالة العقد بناءً على التاريخ (للعرض فقط)
                            $isExpired = $endDate->isPast();
                            $isExpiringSoon = $daysLeft <= 3 && $daysLeft > 0;
                            $isExpiringToday = $daysLeft == 0;
                            $isNotStarted = $startDate->isFuture();
                            
                            // تحديد فئة CSS للصف
                            $rowClass = '';
                            if ($isExpired) {
                                $rowClass = 'expired-row';
                            } elseif ($isExpiringSoon || $isExpiringToday) {
                                $rowClass = 'expiring-row';
                            } elseif ($isNotStarted) {
                                $rowClass = 'not-started-row';
                            }
                            
                            $dateError = false;
                        } catch (Exception $e) {
                            $dateError = true;
                            $rowClass = '';
                        }
                    } else {
                        $dateError = true;
                        $rowClass = '';
                    }
                    
                    // تحديد فئة ونص الحالة من قاعدة البيانات كما هي
                    $statusClass = match ($contract->status) {
                        'active' => 'active',
                        'terminated' => 'terminated',
                        'cancelled' => 'cancelled',
                        'draft' => 'draft',
                        default => ''
                    };

                    $statusLabel = match ($contract->status) {
                        'active' => 'نشط',
                        'terminated' => 'منتهي',
                        'cancelled' => 'ملغي',
                        'draft' => 'مسودة',
                        default => 'غير معروف'
                    };
                @endphp
                
                <tr class="{{ $rowClass }}">
                    <td>
                        {{ $contract->tenant->first_name ?? '' }} {{ $contract->tenant->last_name ?? '' }}
                        
                        @if(!$dateError && $hasValidDates)
                            @if($isNotStarted)
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i>
                                    <span>يبدأ العقد في {{ $startDate->format('Y-m-d') }}</span>
                                </div>
                            @elseif($isExpiringToday && $contract->status === 'active')
                                <div class="alert alert-danger">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    <span>ينتهي العقد اليوم</span>
                                </div>
                            @elseif($isExpiringSoon && $contract->status === 'active')
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    <span>ينتهي العقد خلال {{ $daysLeft }} {{ $daysLeft == 1 ? 'يوم' : 'أيام' }}</span>
                                </div>
                            @elseif($isExpired && $contract->status === 'active')
                                <div class="alert alert-danger">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    <span>العقد منتهي - يحتاج تحديث الحالة</span>
                                </div>
                            @endif
                        @endif
                    </td>
                    
                    <td>{{ $contract->property->name ?? '---' }}</td>
                    <td>{{ $contract->unit->unit_number ?? '---' }}</td>
                    
                    <td>
                        @if($hasValidDates)
                            {{ $contract->start_date }}
                            @if(!$dateError && $isNotStarted)
                                <small class="text-muted">(لم يبدأ)</small>
                            @endif
                        @else
                            <span class="text-danger">غير محدد</span>
                        @endif
                    </td>
                    
                    <td>
                        @if($hasValidDates)
                            {{ $contract->end_date }}
                            @if(!$dateError && $isExpired && $contract->status === 'active')
                                <small class="text-danger">(منتهي)</small>
                            @endif
                        @else
                            <span class="text-danger">غير محدد</span>
                        @endif
                    </td>
                    
                    <td>
                        <span class="status {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    
                    <td>{{ $contract->reference_number }}</td>
                    
                    <td class="action-btns">
                        <a href="{{ route('contracts.show', $contract) }}" class="btn-show">عرض</a>
                        <a href="{{ route('contracts.edit', $contract) }}" class="btn-edit">تعديل</a>
                        
                        <form action="{{ route('contracts.destroy', $contract) }}" method="POST" 
                              onsubmit="return confirm('هل أنت متأكد من حذف هذا العقد؟')" 
                              style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">حذف</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px;">
                        <i class="fa fa-inbox" style="font-size: 48px; color: #ccc; margin-bottom: 10px;"></i>
                        <br>
                        <strong>لا توجد عقود حالياً</strong>
                        <br>
                        <small class="text-muted">يمكنك إضافة عقد جديد من خلال الضغط على زر "إضافة عقد"</small>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- إحصائيات سريعة --}}
@if($contracts->count() > 0)
    <div style="margin-top: 30px; text-align: center;">
        @php
            $activeCount = $contracts->where('status', 'active')->count();
            $terminatedCount = $contracts->where('status', 'terminated')->count();
            $cancelledCount = $contracts->where('status', 'cancelled')->count();
            $draftCount = $contracts->where('status', 'draft')->count();
            
            // حساب العقود التي تنتهي قريباً (من العقود النشطة فقط)
            $expiringSoonCount = 0;
            foreach($contracts->where('status', 'active') as $contract) {
                if ($contract->start_date && $contract->end_date) {
                    try {
                        $endDate = Carbon::parse($contract->end_date);
                        $today = Carbon::today();
                        $daysLeft = $today->diffInDays($endDate, false);
                        
                        if ($daysLeft <= 3 && $daysLeft >= 0) {
                            $expiringSoonCount++;
                        }
                    } catch (Exception $e) {
                        // تجاهل العقود ذات التواريخ غير الصحيحة
                    }
                }
            }
        @endphp
        
        <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
            <div style="background: #d4edda; color: #155724; padding: 10px 20px; border-radius: 8px;">
                <strong>العقود النشطة: {{ $activeCount }}</strong>
            </div>
            
            @if($expiringSoonCount > 0)
                <div style="background: #fff3cd; color: #856404; padding: 10px 20px; border-radius: 8px;">
                    <strong>تنتهي قريباً: {{ $expiringSoonCount }}</strong>
                </div>
            @endif
            
            <div style="background: #e2e3e5; color: #383d41; padding: 10px 20px; border-radius: 8px;">
                <strong>العقود المنتهية: {{ $terminatedCount }}</strong>
            </div>
            
            @if($cancelledCount > 0)
                <div style="background: #f8d7da; color: #721c24; padding: 10px 20px; border-radius: 8px;">
                    <strong>العقود الملغية: {{ $cancelledCount }}</strong>
                </div>
            @endif
            
            @if($draftCount > 0)
                <div style="background: #fff3cd; color: #856404; padding: 10px 20px; border-radius: 8px;">
                    <strong>المسودات: {{ $draftCount }}</strong>
                </div>
            @endif
        </div>
    </div>
@endif

@endsection