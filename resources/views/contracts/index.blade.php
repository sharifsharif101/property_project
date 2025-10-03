@extends('layouts.app')

@php
    use Carbon\Carbon;
@endphp

@section('title', 'قائمة العقود')

@section('content')
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-md shadow-sm" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-white rounded-lg shadow-md">
        <!-- Card Header -->
        <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="text-xl font-bold text-gray-800">قائمة العقود ({{ $contracts->count() }})</h2>
            <a href="{{ route('contracts.create') }}" 
               class="inline-flex items-center gap-2 bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd" /></svg>
                <span>إضافة عقد جديد</span>
            </a>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <div class="overflow-x-auto">
                <table id="contractsTable" class="w-full text-xs text-right">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="p-3 font-medium text-sm">المستأجر</th>
                            <th class="p-3 font-medium text-sm">العقار / الوحدة</th>
                            <th class="p-3 font-medium text-sm">فترة العقد</th>
                            <th class="p-3 font-medium text-sm text-center">الحالة</th>
                            <th class="p-3 font-medium text-sm">المرجع</th>
                            <th class="p-3 font-medium text-sm text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($contracts as $contract)
                            @php
                                $rowClass = '';
                                $daysLeft = null;
                                if ($contract->start_date && $contract->end_date) {
                                    $endDate = Carbon::parse($contract->end_date);
                                    $daysLeft = Carbon::today()->diffInDays($endDate, false);

                                    if ($endDate->isPast()) $rowClass = 'border-r-4 border-red-400';
                                    elseif ($daysLeft <= 30) $rowClass = 'border-r-4 border-yellow-400';
                                    elseif (Carbon::parse($contract->start_date)->isFuture()) $rowClass = 'border-r-4 border-blue-400';
                                }
                                
                                $statusConfig = [
                                    'active' => ['label' => 'نشط', 'style' => 'bg-green-100 text-green-800'],
                                    'terminated' => ['label' => 'منتهي', 'style' => 'bg-gray-200 text-gray-700'],
                                    'cancelled' => ['label' => 'ملغي', 'style' => 'bg-red-100 text-red-800'],
                                    'draft' => ['label' => 'مسودة', 'style' => 'bg-yellow-100 text-yellow-800'],
                                ];
                                $config = $statusConfig[$contract->status] ?? ['label' => $contract->status, 'style' => 'bg-gray-100'];
                            @endphp
                            
                            <tr class="hover:bg-gray-50 {{ $rowClass }}">
                                <td class="p-3 whitespace-nowrap">
                                    <div class="font-semibold text-sm text-gray-900">{{ $contract->tenant->first_name ?? '' }} {{ $contract->tenant->last_name ?? 'N/A' }}</div>
                                    <div class="text-gray-500">{{ $contract->tenant->phone ?? '' }}</div>
                                </td>
                                <td class="p-3 whitespace-nowrap">
                                    <div class="font-medium text-gray-800">{{ $contract->property->name ?? 'N/A' }}</div>
                                    <div class="text-gray-500">وحدة: {{ $contract->unit->unit_number ?? 'N/A' }}</div>
                                </td>
                                <td class="p-3 whitespace-nowrap">
                                    <div class="text-gray-800" dir="ltr">{{ $contract->start_date ? Carbon::parse($contract->start_date)->format('Y-m-d') : 'N/A' }}</div>
                                    <div class="text-gray-500" dir="ltr">إلى {{ $contract->end_date ? Carbon::parse($contract->end_date)->format('Y-m-d') : 'N/A' }}</div>
                                    @if($daysLeft !== null && $contract->status === 'active')
                                        @if($daysLeft < 0)
                                            <span class="mt-1 font-bold text-red-600">منتهي منذ {{ abs($daysLeft) }} يوم</span>
                                        @elseif($daysLeft <= 30)
                                            <span class="mt-1 font-bold text-yellow-700">متبقي {{ $daysLeft }} يوم</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="p-3 text-center">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 font-semibold {{ $config['style'] }}">
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                                <td class="p-3 text-gray-600 font-mono">{{ $contract->reference_number }}</td>
                                <td class="p-3 text-center">
                                    <div class="flex items-center justify-center gap-x-2">
                                        <a href="{{ route('contracts.show', $contract) }}" class="relative group">
                                            <div class="w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full hover:bg-blue-200"><svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg></div>
                                            <span class="tooltip">عرض</span>
                                        </a>
                                        <a href="{{ route('contracts.edit', $contract) }}" class="relative group">
                                            <div class="w-8 h-8 flex items-center justify-center bg-yellow-100 text-yellow-600 rounded-full hover:bg-yellow-200"><svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg></div>
                                            <span class="tooltip">تعديل</span>
                                        </a>
                                        <form action="{{ route('contracts.destroy', $contract) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا العقد؟')" class="relative group">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-100 text-red-600 rounded-full hover:bg-red-200">
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                            </button>
                                            <span class="tooltip">حذف</span>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-16 text-gray-500">
                                    لا توجد عقود لعرضها حالياً.
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
    {{-- Custom Tooltip Style --}}
    <style>
        html { color-scheme: light; }
        .tooltip { @apply absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs rounded-md px-2 py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity whitespace-nowrap; }
    </style>
@endpush