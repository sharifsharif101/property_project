 @extends('layouts.app')

@section('title', 'تفاصيل الوحدة')

@section('content')
    <div class="space-y-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <p class="text-sm text-gray-500">لوحة التحكم / الوحدات</p>
                <h1 class="text-2xl font-bold text-gray-800">تفاصيل الوحدة #{{ $unit->unit_number }}</h1>
            </div>
            <a href="{{ route('units.index') }}" 
               class="inline-flex items-center gap-2 bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow-sm hover:bg-gray-300 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                <span>العودة للقائمة</span>
            </a>
        </div>

        <!-- Unit Details Card -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">التفاصيل الأساسية للوحدة</h3>
            </div>
            <div class="p-6">
                {{-- Using a definition list for better semantics and styling --}}
                <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-8">
                    <div class="space-y-1">
                        <dt class="text-sm font-medium text-gray-500">العقار</dt>
                        <dd class="text-base font-semibold text-gray-900">{{ $unit->property->name ?? 'غير معروف' }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-sm font-medium text-gray-500">رقم الوحدة</dt>
                        <dd class="text-base font-semibold text-gray-900">{{ $unit->unit_number }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-sm font-medium text-gray-500">الحالة</dt>
                        <dd>
                            @php
                                $statusConfig = [
                                    'available' => ['label' => 'متاحة', 'style' => 'bg-green-100 text-green-800'],
                                    'reserved' => ['label' => 'محجوزة', 'style' => 'bg-cyan-100 text-cyan-800'],
                                    'rented' => ['label' => 'مؤجرة', 'style' => 'bg-blue-100 text-blue-800'],
                                    'under_maintenance' => ['label' => 'تحت الصيانة', 'style' => 'bg-yellow-100 text-yellow-800'],
                                    'disabled' => ['label' => 'معطلة', 'style' => 'bg-gray-200 text-gray-800'],
                                ];
                                $config = $statusConfig[$unit->status] ?? ['label' => 'غير معروف', 'style' => 'bg-gray-200 text-gray-800'];
                            @endphp
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $config['style'] }}">
                                {{ $config['label'] }}
                            </span>
                        </dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-sm font-medium text-gray-500">عدد غرف النوم</dt>
                        <dd class="text-base font-semibold text-gray-900">{{ $unit->bedrooms }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-sm font-medium text-gray-500">عدد الحمامات</dt>
                        <dd class="text-base font-semibold text-gray-900">{{ $unit->bathrooms }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-sm font-medium text-gray-500">المساحة (م²)</dt>
                        <dd class="text-base font-semibold text-gray-900">{{ $unit->area }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-sm font-medium text-gray-500">الطابق</dt>
                        <dd class="text-base font-semibold text-gray-900">{{ $unit->floor_number }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Associated Contracts Card -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">سجل العقود المرتبطة بالوحدة</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse ($unit->contracts as $contract)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-center">
                            <div>
                                <a href="{{ route('contracts.show', $contract->id) }}" class="font-bold text-blue-600 hover:underline">
                                    عقد #{{ $contract->reference_number }}
                                </a>
                                <p class="text-sm text-gray-600 mt-1">
                                    المستأجر: <span class="font-medium">{{ $contract->tenant->full_name ?? 'غير محدد' }}</span>
                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    من {{ \Carbon\Carbon::parse($contract->start_date)->format('Y/m/d') }} إلى {{ \Carbon\Carbon::parse($contract->end_date)->format('Y/m/d') }}
                                </p>
                            </div>
                            <div>
                                @php
                                    $contractStatusConfig = [
                                        'active' => ['label' => 'ساري', 'style' => 'bg-green-100 text-green-800'],
                                        'expired' => ['label' => 'منتهي', 'style' => 'bg-red-100 text-red-800'],
                                        'draft' => ['label' => 'مسودة', 'style' => 'bg-yellow-100 text-yellow-800'],
                                    ];
                                    $contractConfig = $contractStatusConfig[$contract->status] ?? ['label' => $contract->status, 'style' => 'bg-gray-200'];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $contractConfig['style'] }}">
                                    {{ $contractConfig['label'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        <p>لا توجد عقود مرتبطة بهذه الوحدة حالياً.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection