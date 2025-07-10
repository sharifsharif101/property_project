@extends('layouts.app')

@section('title', 'قائمة الوحدات')

@section('content')
    {{-- Session Messages with Alpine.js for auto-hiding --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
            class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-md shadow-sm" role="alert">
            <strong class="font-bold">نجاح!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
            class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-md shadow-sm" role="alert">
            <strong class="font-bold">خطأ!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @php
        $statusConfig = [
            'available' => ['label' => 'متاحة', 'style' => 'bg-green-100 text-green-800'],
            'reserved' => ['label' => 'محجوزة', 'style' => 'bg-cyan-100 text-cyan-800'],
            'rented' => ['label' => 'مؤجرة', 'style' => 'bg-blue-100 text-blue-800'],
            'under_maintenance' => ['label' => 'تحت الصيانة', 'style' => 'bg-yellow-100 text-yellow-800'],
            'disabled' => ['label' => 'معطلة', 'style' => 'bg-gray-200 text-gray-800'],
        ];
    @endphp

    <!-- Main Card -->
    <div class="bg-white rounded-lg shadow-md">
        <!-- Card Header -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">قائمة الوحدات</h2>
        </div>

        <!-- Card Body -->
        <div class="p-6 space-y-4">
            <!-- Custom Buttons Section -->
            <div class="flex flex-col sm:flex-row justify-end items-center gap-3">
                <button id="toggleViewBtn" type="button" class="font-medium rounded-lg text-sm px-4 py-2 text-white bg-green-600 hover:bg-green-700 focus:outline-none">
                    عرض مجمّع
                </button>
                <button id="deleteSelectedBtn" class="hidden inline-flex items-center gap-2 font-medium rounded-lg text-sm px-4 py-2 text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                    <span>حذف المحدد</span>
                </button>
            </div>

            <!-- Full Table View (DataTables will take control here) -->
            <div id="fullTableContainer" class="overflow-x-auto">
                <table id="fullTable" class="w-full text-sm text-right">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="p-4 w-4"><input type="checkbox" id="selectAll" class="rounded border-gray-300 focus:ring-blue-500"></th>
                            <th class="p-4 font-medium">#</th>
                            <th class="p-4 font-medium">العقار</th>
                            <th class="p-4 font-medium">رقم الوحدة</th>
                            <th class="p-4 font-medium text-center">الحالة</th>
                            <th class="p-4 font-medium">أضيف بتاريخ</th>
                            <th class="p-4 font-medium text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($units as $unit)
                            <tr data-unit-id="{{ $unit->id }}" class="hover:bg-gray-50">
                                <td class="p-4"><input type="checkbox" class="rowCheckbox rounded border-gray-300 focus:ring-blue-500" value="{{ $unit->id }}"></td>
                                <td class="p-4 text-gray-700">{{ $loop->iteration }}</td>
                                <td class="p-4 font-medium text-gray-900">{{ $unit->property->name ?? 'غير معروف' }}</td>
                                <td class="p-4 text-gray-700">{{ $unit->unit_number }}</td>
                                <td class="p-4 text-center">
                                    @php $config = $statusConfig[$unit->status] ?? ['label' => $unit->status, 'style' => 'bg-gray-200 text-gray-800']; @endphp
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $config['style'] }}">{{ $config['label'] }}</span>
                                </td>
                                <td class="p-4 text-gray-600" dir="ltr">{{ $unit->created_at->format('Y-m-d') }}</td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center gap-x-3">
                                        <a href="{{ route('units.show', $unit->id) }}" class="text-gray-500 hover:text-blue-600" title="عرض"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg></a>
                                        @if($unit->contracts()->whereIn('status', ['active', 'draft'])->exists())
                                            <span class="text-gray-300 cursor-not-allowed" title="لا يمكن تعديل وحدة مرتبطة بعقد نشط"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg></span>
                                        @else
                                            <a href="{{ route('units.edit', $unit) }}" class="text-gray-500 hover:text-yellow-600" title="تعديل"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg></a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-16 text-gray-500">لا توجد وحدات لعرضها.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Grouped View -->
            <div id="groupedView" class="hidden space-y-6">
                @forelse ($groupedUnits as $propertyName => $propertyUnits)
                    <div class="property-group border border-gray-200 rounded-lg shadow-sm">
                        <button class="expand-collapse-btn w-full flex items-center justify-between bg-gray-50 p-4 rounded-t-lg text-right hover:bg-gray-100 transition">
                            <h3 class="font-bold text-lg text-gray-800">{{ $propertyName }} ({{ $propertyUnits->count() }} وحدات)</h3>
                            <span class="text-sm font-medium text-blue-600">عرض الوحدات</span>
                        </button>
                        <div class="units-list hidden p-4">
                            <div class="mb-4"><label class="inline-flex items-center gap-2"><input type="checkbox" class="selectAllGrouped rounded border-gray-300"> <span class="text-sm">تحديد جميع وحدات هذا العقار</span></label></div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-right">
                                    <thead class="bg-gray-50 text-gray-600">
                                        <tr>
                                            <th class="p-3 w-4"><input type="checkbox" disabled class="rounded border-gray-300"></th>
                                            <th class="p-3 font-medium">رقم الوحدة</th>
                                            <th class="p-3 font-medium text-center">الحالة</th>
                                            <th class="p-3 font-medium text-center">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach ($propertyUnits as $unit)
                                            <tr data-unit-id="{{ $unit->id }}" class="hover:bg-gray-50">
                                                <td class="p-3"><input type="checkbox" class="rowCheckbox grouped-checkbox rounded border-gray-300" value="{{ $unit->id }}"></td>
                                                <td class="p-3 text-gray-700">{{ $unit->unit_number }}</td>
                                                <td class="p-3 text-center">
                                                    @php $config = $statusConfig[$unit->status] ?? ['label' => $unit->status, 'style' => 'bg-gray-200 text-gray-800']; @endphp
                                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $config['style'] }}">{{ $config['label'] }}</span>
                                                </td>
                                                <td class="p-3">
                                                    <div class="flex items-center justify-center gap-x-3">
                                                        <a href="{{ route('units.show', $unit->id) }}" class="text-gray-500 hover:text-blue-600" title="عرض"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg></a>
                                                        @if($unit->contracts()->whereIn('status', ['active', 'draft'])->exists())
                                                            <span class="text-gray-300 cursor-not-allowed" title="لا يمكن تعديل وحدة مرتبطة بعقد نشط"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg></span>
                                                        @else
                                                            <a href="{{ route('units.edit', $unit) }}" class="text-gray-500 hover:text-yellow-600" title="تعديل"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg></a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 text-gray-500">لا توجد عقارات أو وحدات لعرضها في العرض المجمع.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- DataTables Initialization --}}
    <script>
        $(document).ready(function() {
            $('#fullTable').DataTable({
                "language": { "url": "https://cdn.datatables.net/plug-ins/2.0.3/i18n/ar.json" },
                "columnDefs": [ { "orderable": false, "targets": [0, 6] } ]
            });
        });
    </script>
    
    {{-- Custom JS for View Toggle and Bulk Actions --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleViewBtn = document.getElementById('toggleViewBtn');
            const fullTableContainer = document.getElementById('fullTableContainer');
            const groupedView = document.getElementById('groupedView');
            const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');

            toggleViewBtn.addEventListener('click', function () {
                fullTableContainer.classList.toggle('hidden');
                groupedView.classList.toggle('hidden');
                toggleViewBtn.textContent = groupedView.classList.contains('hidden') ? 'عرض مجمّع' : 'عرض كامل';
                updateDeleteButtonVisibility();
            });

            const selectAllCheckbox = document.getElementById('selectAll');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function () {
                    fullTableContainer.querySelectorAll('.rowCheckbox').forEach(checkbox => checkbox.checked = this.checked);
                    updateDeleteButtonVisibility();
                });
            }

            document.querySelectorAll('.selectAllGrouped').forEach(selectAll => {
                selectAll.addEventListener('change', function () {
                    const parentGroup = this.closest('.property-group');
                    parentGroup.querySelectorAll('.grouped-checkbox').forEach(checkbox => checkbox.checked = this.checked);
                    updateDeleteButtonVisibility();
                });
            });

            document.querySelectorAll('.rowCheckbox').forEach(checkbox => {
                checkbox.addEventListener('change', () => updateDeleteButtonVisibility());
            });

            function updateDeleteButtonVisibility() {
                const anyChecked = document.querySelectorAll('.rowCheckbox:checked').length > 0;
                deleteSelectedBtn.classList.toggle('hidden', !anyChecked);
            }

            deleteSelectedBtn.addEventListener('click', function () {
                const selectedIds = Array.from(document.querySelectorAll('.rowCheckbox:checked')).map(checkbox => checkbox.value);
                if (selectedIds.length === 0) return;

                if (confirm(`هل أنت متأكد من حذف ${selectedIds.length} وحدة؟ هذا الإجراء لا يمكن التراجع عنه.`)) {
                    fetch('{{ route("units.bulkDelete") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ ids: selectedIds })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert(data.error || 'حدث خطأ أثناء الحذف.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ فادح أثناء الاتصال بالخادم.');
                    });
                }
            });

            document.querySelectorAll('.expand-collapse-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const unitsList = this.closest('.property-group').querySelector('.units-list');
                    const span = this.querySelector('span');
                    const isExpanded = unitsList.classList.contains('hidden');
                    
                    unitsList.classList.toggle('hidden');
                    span.textContent = isExpanded ? 'إخفاء الوحدات' : 'عرض الوحدات';
                });
            });
            
            // Initial check on page load
            updateDeleteButtonVisibility();
        });
    </script>
@endpush