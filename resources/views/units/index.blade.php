@extends('layouts.app')

@section('title', 'قائمة الوحدات')

@section('content')

{{-- رسائل الجلسة --}}
@if (session('success'))
<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
<strong class="font-bold">نجاح!</strong>
<span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if (session('error'))
<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
<strong class="font-bold">خطأ!</strong>
<span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif

<section class="content">
<div class="row">
<div class="col-xs-12">
<div class="box">
<div class="box-header flex justify-between items-center">
<h3 class="box-title">قائمة الوحدات</h3>

<div class="flex gap-2">
    <button id="toggleViewBtn" type="button" style="color: white"
        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 
        font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 mt-2.5">
        عرض مجمّع
    </button>

    <button id="deleteSelectedBtn"
        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 hidden">
        🗑 حذف المحدد
    </button>
</div>
</div>

<div class="box-body">
@php
$statusLabels = [
    'available' => 'متاحة',
    'reserved' => 'محجوزة',
    'rented' => 'مؤجرة',
    'under_maintenance' => 'تحت الصيانة',
    'disabled' => 'معطلة',
];
@endphp

{{-- جدول العرض الكامل --}}
<div id="fullTableContainer">
    <table id="fullTable" class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>#</th>
                <th>العقار</th>
                <th>رقم الوحدة</th>
                <th>غرف النوم</th>
                <th>الحمامات</th>
                <th>المساحة (م²)</th>
                <th>الطابق</th>
                <th>الحالة</th>
                <th>أضيف بتاريخ</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($units as $unit)
                <tr data-unit-id="{{ $unit->id }}">
                    <td>
                        <input type="checkbox" class="rowCheckbox" value="{{ $unit->id }}">
                    </td>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $unit->property->name ?? 'غير معروف' }}</td>
                    <td>{{ $unit->unit_number }}</td>
                    <td>{{ $unit->bedrooms }}</td>
                    <td>{{ $unit->bathrooms }}</td>
                    <td>{{ $unit->area }}</td>
                    <td>{{ $unit->floor_number }}</td>
                    <td>
                <span class="status-badge {{ $unit->status }}">
    {{ $statusLabels[$unit->status] ?? $unit->status }}
</span>
                    </td>
                    <td>{{ $unit->created_at->format('Y-m-d') }}</td>
                    <td class="flex gap-2">
                        <a href="{{ route('units.show', $unit->id) }}" 
                           class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                           عرض
                        </a>
                        <a href="{{ route('units.edit', $unit->id) }}" 
                           class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                           تعديل
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">لا توجد وحدات لعرضها.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- العرض المجمع حسب العقار --}}
<div id="groupedView" class="hidden">
    @forelse ($groupedUnits as $propertyName => $propertyUnits)
        <div class="property-group mb-6 border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-t-lg">
                <h3 class="font-bold text-lg text-gray-800">{{ $propertyName }}</h3>
                <button class="expand-collapse-btn px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600" data-expanded="false">عرض الوحدات</button>
            </div>
            <div class="units-list hidden">
                @if ($propertyUnits->isEmpty())
                    <p class="p-4 text-center text-gray-600">لا توجد وحدات متاحة لهذا العقار.</p>
                @else
                    <div class="p-4">
                        <div class="mb-2">
                            <label><input type="checkbox" class="selectAllGrouped"> تحديد جميع وحدات هذا العقار</label>
                        </div>
                        <table class="table table-bordered table-hover table-striped w-full">
                            <thead>
                                <tr>
                                    <th>تحديد</th>
                                    <th>رقم الوحدة</th>
                                    <th>غرف النوم</th>
                                    <th>الحمامات</th>
                                    <th>المساحة (م²)</th>
                                    <th>الطابق</th>
                                    <th>الحالة</th>
                                    <th>أضيف بتاريخ</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($propertyUnits as $unit)
                                    <tr data-unit-id="{{ $unit->id }}">
                                        <td>
                                            <input type="checkbox" class="rowCheckbox grouped-checkbox" value="{{ $unit->id }}">
                                        </td>
                                        <td>{{ $unit->unit_number }}</td>
                                        <td>{{ $unit->bedrooms }}</td>
                                        <td>{{ $unit->bathrooms }}</td>
                                        <td>{{ $unit->area }}</td>
                                        <td>{{ $unit->floor_number }}</td>
                                        <td>{{ $statusLabels[$unit->status] ?? $unit->status }}</td>
                                        <td>{{ $unit->created_at->format('Y-m-d') }}</td>
                                        <td class="flex gap-2">
                                            <a href="{{ route('units.show', $unit->id) }}" 
                                               class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                               عرض
                                            </a>
                                            <a href="{{ route('units.edit', $unit->id) }}" 
                                               class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                               تعديل
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <p class="p-4 text-center text-gray-600">لا توجد عقارات أو وحدات لعرضها في العرض المجمع.</p>
    @endforelse
</div>

</div>
</div>
</div>
</div>
</section>

<style>
@keyframes fade-in {
from {
opacity: 0;
transform: scale(0.9) translate(-50%, -50%);
}

to {
opacity: 1;
transform: scale(1) translate(-50%, -50%);
}
}

@keyframes fade-out {
from {
opacity: 1;
transform: scale(1) translate(-50%, -50%);
}

to {
opacity: 0;
transform: scale(0.9) translate(-50%, -50%);
}
}

.animate-fade-in {
animation: fade-in 0.3s ease-out forwards;
}

.animate-fade-out {
animation: fade-out 0.3s ease-in forwards;
}

.hidden {
display: none;
}

.units-list {
transition: all 0.3s ease-in-out;
}

/* تحسين مظهر الأزرار */
.box-header {
padding: 15px;
border-bottom: 1px solid #f4f4f4;
}

.box-header .flex {
display: flex;
justify-content: space-between;
align-items: center;
}

.box-header .flex>div {
display: flex;
gap: 10px;
}

/* تحسين مظهر الجداول */
.table {
margin-bottom: 0;
}

.table th {
background-color: #f9f9f9;
font-weight: bold;
}

/* تحسين مظهر العرض المجمع */
.property-group {
margin-bottom: 20px;
}

.property-group:last-child {
margin-bottom: 0;
}

/* تحسين مظهر صناديق الاختيار */
input[type="checkbox"] {
margin: 0;
cursor: pointer;
}
    .status-badge {
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 13px;
        display: inline-block;
    }

    .available { background-color: #d4edda; color: #155724; }
    .reserved { background-color: #fff3cd; color: #856404; }
    .rented { background-color: #bee5eb; color: #0c5460; }
    .under_maintenance { background-color: #f8d7da; color: #721c24; }
    .disabled { background-color: #e2e3e5; color: #383d41; }
/* تحسين مظهر الأزرار */
button:disabled {
opacity: 0.6;
cursor: not-allowed;
}

/* تحسين التحرير المباشر */
.editable:focus {
background-color: #fff3cd;
outline: 2px solid #ffc107;
outline-offset: 2px;
}

.editable-select:focus {
outline: 2px solid #007bff;
outline-offset: 2px;
}
</style>

 

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle between full table and grouped view
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

    // Handle "Select All" checkbox in full table
    const selectAllCheckbox = document.getElementById('selectAll');
    selectAllCheckbox.addEventListener('change', function () {
        document.querySelectorAll('.rowCheckbox').forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        updateDeleteButtonVisibility();
    });

    // Handle "Select All" checkboxes in grouped view
    document.querySelectorAll('.selectAllGrouped').forEach(selectAll => {
        selectAll.addEventListener('change', function () {
            const parentGroup = selectAll.closest('.property-group');
            parentGroup.querySelectorAll('.grouped-checkbox').forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            updateDeleteButtonVisibility();
        });
    });

    // Handle individual checkbox changes
    document.querySelectorAll('.rowCheckbox').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            updateDeleteButtonVisibility();
        });
    });

    // Update visibility of delete button
    function updateDeleteButtonVisibility() {
        const checkedCheckboxes = document.querySelectorAll('.rowCheckbox:checked');
        deleteSelectedBtn.classList.toggle('hidden', checkedCheckboxes.length === 0);
    }

    // Handle bulk delete
    deleteSelectedBtn.addEventListener('click', function () {
        const selectedIds = Array.from(document.querySelectorAll('.rowCheckbox:checked')).map(checkbox => checkbox.value);

        if (selectedIds.length === 0) {
            alert('يرجى تحديد وحدة واحدة على الأقل للحذف.');
            return;
        }

        if (confirm('هل أنت متأكد من حذف الوحدات المحددة؟')) {
            fetch('{{ route("units.bulkDelete") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove deleted rows from the table
                    selectedIds.forEach(id => {
                        const row = document.querySelector(`tr[data-unit-id="${id}"]`);
                        if (row) {
                            row.remove();
                        }
                    });
                    // Show success message
                    const successDiv = document.createElement('div');
                    successDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4';
                    successDiv.innerHTML = `<strong class="font-bold">نجاح!</strong><span class="block sm:inline">${data.success}</span>`;
                    document.querySelector('.content').prepend(successDiv);
                    setTimeout(() => successDiv.remove(), 3000);
                    updateDeleteButtonVisibility();
                } else {
                    // Show error message
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
                    errorDiv.innerHTML = `<strong class="font-bold">خطأ!</strong><span class="block sm:inline">${data.error}</span>`;
                    document.querySelector('.content').prepend(errorDiv);
                    setTimeout(() => errorDiv.remove(), 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
                errorDiv.innerHTML = `<strong class="font-bold">خطأ!</strong><span class="block sm:inline">حدث خطأ أثناء الحذف.</span>`;
                document.querySelector('.content').prepend(errorDiv);
                setTimeout(() => errorDiv.remove(), 3000);
            });
        }
    });

    // Handle expand/collapse for grouped view
    document.querySelectorAll('.expand-collapse-btn').forEach(button => {
        button.addEventListener('click', function () {
            const unitsList = this.closest('.property-group').querySelector('.units-list');
            const isExpanded = this.getAttribute('data-expanded') === 'true';
            unitsList.classList.toggle('hidden');
            this.setAttribute('data-expanded', !isExpanded);
            this.textContent = isExpanded ? 'عرض الوحدات' : 'إخفاء الوحدات';
        });
    });
});
</script>
</section>
@endsection