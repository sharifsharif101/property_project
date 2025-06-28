@extends('layouts.app')

@section('title', 'قائمة الوحدات')

@section('content')
{{-- Session messages (for actions that cause a full page reload) --}}
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
                        <button id="toggleViewBtn" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
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
                            'vacant' => 'شاغرة',
                            'rented' => 'مؤجرة',
                            'under_maintenance' => 'تحت الصيانة',
                            'under_renovation' => 'تحت التجديد',
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
                                    <th>تحديث</th>
                                    <th>محذوف؟</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($units as $unit)
                                    <tr data-unit-id="{{ $unit->id }}">
                                        <td><input type="checkbox" class="rowCheckbox" value="{{ $unit->id }}"></td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $unit->property->name ?? 'غير معروف' }}</td>
                                        <td><span class="editable" data-id="{{ $unit->id }}" data-field="unit_number" contenteditable="true">{{ $unit->unit_number }}</span></td>
                                        <td><span class="editable" data-id="{{ $unit->id }}" data-field="bedrooms" contenteditable="true">{{ $unit->bedrooms }}</span></td>
                                        <td><span class="editable" data-id="{{ $unit->id }}" data-field="bathrooms" contenteditable="true">{{ $unit->bathrooms }}</span></td>
                                        <td><span class="editable" data-id="{{ $unit->id }}" data-field="area" contenteditable="true">{{ $unit->area }}</span></td>
                                        <td><span class="editable" data-id="{{ $unit->id }}" data-field="floor_number" contenteditable="true">{{ $unit->floor_number }}</span></td>
                                        <td>
                                            <select class="editable-select form-control" data-id="{{ $unit->id }}" data-field="status">
                                                @foreach ($statusLabels as $key => $label)
                                                    <option value="{{ $key }}" {{ $unit->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>{{ $unit->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $unit->updated_at->format('Y-m-d') }}</td>
                                        <td class="text-center">{{ $unit->deleted_at ? 'نعم' : '' }}</td>
                                        <td>
                                            <a href="{{ url('/units/' . $unit->id) }}" class="btn btn-sm btn-info" title="عرض"><i class="fa fa-eye"></i></a>
                                            <a href="{{ url('/units/' . $unit->id . '/edit') }}" class="btn btn-sm btn-warning" title="تعديل"><i class="fa fa-edit"></i></a>
                                            <form action="{{ url('/units/' . $unit->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟');">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center">لا توجد وحدات لعرضها.</td>
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
                                    <button class="expand-collapse-btn px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600" 
                                            data-expanded="false">
                                        عرض الوحدات
                                    </button>
                                </div>
                                <div class="units-list hidden">
                                    @if ($propertyUnits->isEmpty())
                                        <p class="p-4 text-center text-gray-600">لا توجد وحدات متاحة لهذا العقار.</p>
                                    @else
                                        <div class="p-4">
                                            <div class="mb-2">
                                                <label>
                                                    <input type="checkbox" class="selectAllGrouped"> 
                                                    تحديد جميع وحدات هذا العقار
                                                </label>
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
                                                        <th>العمليات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($propertyUnits as $unit)
                                                        <tr data-unit-id="{{ $unit->id }}">
                                                            <td><input type="checkbox" class="rowCheckbox grouped-checkbox" value="{{ $unit->id }}"></td>
                                                            <td><span class="editable" data-id="{{ $unit->id }}" data-field="unit_number" contenteditable="true">{{ $unit->unit_number }}</span></td>
                                                            <td><span class="editable" data-id="{{ $unit->id }}" data-field="bedrooms" contenteditable="true">{{ $unit->bedrooms }}</span></td>
                                                            <td><span class="editable" data-id="{{ $unit->id }}" data-field="bathrooms" contenteditable="true">{{ $unit->bathrooms }}</span></td>
                                                            <td><span class="editable" data-id="{{ $unit->id }}" data-field="area" contenteditable="true">{{ $unit->area }}</span></td>
                                                            <td><span class="editable" data-id="{{ $unit->id }}" data-field="floor_number" contenteditable="true">{{ $unit->floor_number }}</span></td>
                                                            <td>
                                                                <select class="editable-select form-control" data-id="{{ $unit->id }}" data-field="status">
                                                                    @foreach ($statusLabels as $key => $label)
                                                                        <option value="{{ $key }}" {{ $unit->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>{{ $unit->created_at->format('Y-m-d') }}</td>
                                                            <td>
                                                                <a href="{{ url('/units/' . $unit->id) }}" class="btn btn-sm btn-info" title="عرض"><i class="fa fa-eye"></i></a>
                                                                <a href="{{ url('/units/' . $unit->id . '/edit') }}" class="btn btn-sm btn-warning" title="تعديل"><i class="fa fa-edit"></i></a>
                                                                <form action="{{ url('/units/' . $unit->id) }}" method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟');">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </form>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // المتغيرات الأساسية
            const toggleViewBtn = document.getElementById('toggleViewBtn');
            const fullTableContainer = document.getElementById('fullTableContainer');
            const groupedView = document.getElementById('groupedView');
            const deleteBtn = document.getElementById('deleteSelectedBtn');
            const selectAll = document.getElementById('selectAll');

            // إعداد عرض/إخفاء الجداول
            let isGroupedView = false;

            function updateToggleButton() {
                toggleViewBtn.textContent = isGroupedView ? 'عرض كامل' : 'عرض مجمّع';
            }

            function toggleView() {
                isGroupedView = !isGroupedView;
                
                if (isGroupedView) {
                    fullTableContainer.classList.add('hidden');
                    groupedView.classList.remove('hidden');
                } else {
                    fullTableContainer.classList.remove('hidden');
                    groupedView.classList.add('hidden');
                }
                
                updateToggleButton();
                updateDeleteBtnVisibility();
            }

            toggleViewBtn.addEventListener('click', toggleView);

            // إعداد أزرار توسيع/طي المجموعات
            function setupExpandCollapseButtons() {
                document.querySelectorAll('.expand-collapse-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const unitsList = this.closest('.property-group').querySelector('.units-list');
                        const isExpanded = this.dataset.expanded === 'true';
                        
                        if (isExpanded) {
                            unitsList.classList.add('hidden');
                            this.textContent = 'عرض الوحدات';
                            this.dataset.expanded = 'false';
                        } else {
                            unitsList.classList.remove('hidden');
                            this.textContent = 'إخفاء الوحدات';
                            this.dataset.expanded = 'true';
                        }
                        
                        // تحديث زر الحذف بعد تغيير العرض
                        setTimeout(updateDeleteBtnVisibility, 100);
                    });
                });
            }

            setupExpandCollapseButtons();

            // إدارة تحديد الصفوف
            function getAllVisibleCheckboxes() {
                if (isGroupedView) {
                    // في العرض المجمع، نأخذ فقط الصناديق من المجموعات المفتوحة
                    const visibleCheckboxes = [];
                    document.querySelectorAll('.property-group').forEach(group => {
                        const unitsList = group.querySelector('.units-list');
                        const expandBtn = group.querySelector('.expand-collapse-btn');
                        
                        // تحقق من أن المجموعة مفتوحة
                        if (expandBtn && expandBtn.dataset.expanded === 'true' && !unitsList.classList.contains('hidden')) {
                            unitsList.querySelectorAll('.rowCheckbox').forEach(cb => {
                                visibleCheckboxes.push(cb);
                            });
                        }
                    });
                    return visibleCheckboxes;
                } else {
                    // في العرض الكامل
                    return Array.from(document.querySelectorAll('#fullTable .rowCheckbox'));
                }
            }

            function updateDeleteBtnVisibility() {
                const visibleCheckboxes = getAllVisibleCheckboxes();
                const anyChecked = visibleCheckboxes.some(checkbox => checkbox.checked);
                deleteBtn.classList.toggle('hidden', !anyChecked);
            }

            // تحديد الكل في الجدول الرئيسي
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('#fullTable .rowCheckbox');
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateDeleteBtnVisibility();
                });
            }

            // تحديد الكل في المجموعات
            function setupGroupedSelectAll() {
                document.querySelectorAll('.selectAllGrouped').forEach(groupCheckbox => {
                    groupCheckbox.addEventListener('change', function() {
                        const container = this.closest('.units-list');
                        const checkboxes = container.querySelectorAll('.grouped-checkbox');
                        checkboxes.forEach(cb => cb.checked = this.checked);
                        updateDeleteBtnVisibility();
                    });
                });
            }

            setupGroupedSelectAll();

            // تحديث زر الحذف عند تغيير أي صندوق
            function setupCheckboxListeners() {
                document.addEventListener('change', function(e) {
                    if (e.target.classList.contains('rowCheckbox')) {
                        updateDeleteBtnVisibility();
                        
                        // تحديث حالة "تحديد الكل" في المجموعة
                        if (e.target.classList.contains('grouped-checkbox')) {
                            const container = e.target.closest('.units-list');
                            const groupCheckbox = container.querySelector('.selectAllGrouped');
                            const allGroupCheckboxes = container.querySelectorAll('.grouped-checkbox');
                            const checkedCount = Array.from(allGroupCheckboxes).filter(cb => cb.checked).length;
                            
                            groupCheckbox.checked = checkedCount === allGroupCheckboxes.length;
                            groupCheckbox.indeterminate = checkedCount > 0 && checkedCount < allGroupCheckboxes.length;
                        }
                    }
                });
            }

            setupCheckboxListeners();

            // حذف الصفوف المحددة
            deleteBtn.addEventListener('click', function() {
                const selectedCheckboxes = getAllVisibleCheckboxes().filter(cb => cb.checked);
                const selectedIds = selectedCheckboxes.map(cb => cb.value);

                if (selectedIds.length === 0) {
                    showToast('لم يتم تحديد أي وحدات', 'error');
                    return;
                }

                if (!confirm(`هل أنت متأكد من حذف ${selectedIds.length} وحدة؟`)) return;

                // تعطيل الزر أثناء المعالجة
                deleteBtn.disabled = true;
                deleteBtn.textContent = 'جاري الحذف...';

                fetch(`/units/bulk-delete`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids: selectedIds })
                })
                .then(res => {
                    if (!res.ok) throw new Error('فشل الاتصال بالخادم.');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        // إزالة الصفوف المحذوفة من الواجهة
                        selectedIds.forEach(id => {
                            document.querySelectorAll(`tr[data-unit-id="${id}"]`).forEach(row => {
                                row.remove();
                            });
                        });
                        showToast(data.success, 'success');
                        
                        // إعادة ضبط أزرار التحديد
                        if (selectAll) selectAll.checked = false;
                        document.querySelectorAll('.selectAllGrouped').forEach(cb => {
                            cb.checked = false;
                            cb.indeterminate = false;
                        });
                    } else {
                        showToast(data.error || 'فشل الحذف', 'error');
                    }
                })
                .catch(err => {
                    console.error('Delete error:', err);
                    showToast(err.message || 'حدث خطأ أثناء الحذف', 'error');
                })
                .finally(() => {
                    deleteBtn.disabled = false;
                    deleteBtn.textContent = '🗑 حذف المحدد';
                    updateDeleteBtnVisibility();
                });
            });

            // وظائف التحرير المباشر
            function stripHTML(str) {
                return str.replace(/<\/?[^>]+(>|$)/g, '');
            }

            function isNumericField(field) {
                return ['bedrooms', 'bathrooms', 'area', 'floor_number'].includes(field);
            }

            function validateInput(field, value) {
                if (value === '') return 'القيمة لا يمكن أن تكون فارغة';
                if (isNumericField(field)) {
                    if (isNaN(value) || Number(value) < 0) return 'القيمة يجب أن تكون رقمًا موجبًا';
                }
                return null;
            }

            // إعداد التحرير المباشر للنصوص
            function setupEditableFields() {
                document.querySelectorAll('.editable').forEach(el => {
                    el.dataset.old = el.innerText.trim();

                    el.addEventListener('keydown', e => {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            el.blur();
                        }
                    });

                    el.addEventListener('blur', function() {
                        const unitId = this.dataset.id;
                        const field = this.dataset.field;
                        let newValue = this.innerText.trim();
                        newValue = stripHTML(newValue);
                        const oldValue = this.dataset.old;

                        if (newValue === oldValue) return;

                        const validationError = validateInput(field, newValue);
                        if (validationError) {
                            showToast(validationError, 'error');
                            this.innerText = oldValue;
                            return;
                        }

                        this.setAttribute('contenteditable', 'false');
                        this.style.backgroundColor = '#fef08a';

                        fetch(`/units/${unitId}/update-field`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                field: field,
                                value: newValue
                            })
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Server response not OK');
                            return res.json();
                        })
                        .then(data => {
                            if (data.success) {
                                this.dataset.old = newValue;
                                showToast(data.success, 'success');
                            } else {
                                this.innerText = oldValue;
                                showToast(data.error || 'فشل الحفظ', 'error');
                            }
                        })
                        .catch(err => {
                            console.error('Update error:', err);
                            this.innerText = oldValue;
                            showToast('خطأ في الاتصال بالخادم', 'error');
                        })
                        .finally(() => {
                            this.setAttribute('contenteditable', 'true');
                            this.style.backgroundColor = '';
                        });
                    });
                });
            }

            // إعداد التحرير المباشر للقوائم المنسدلة
            function setupEditableSelects() {
                document.querySelectorAll('.editable-select').forEach(select => {
                    select.dataset.old = select.value;
                    
                    select.addEventListener('change', function() {
                        const unitId = this.dataset.id;
                        const field = this.dataset.field;
                        const newValue = this.value;
                        const oldValue = this.dataset.old;

                        if (newValue === oldValue) return;

                        this.disabled = true;
                        this.style.backgroundColor = '#fef08a';

                        fetch(`/units/${unitId}/update-field`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                field: field,
                                value: newValue
                            })
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Server response not OK');
                            return res.json();
                        })
                        .then(data => {
                            if (data.success) {
                                this.dataset.old = newValue;
                                showToast(data.success, 'success');
                            } else {
                                this.value = oldValue;
                                showToast(data.error || 'فشل الحفظ', 'error');
                            }
                        })
                        .catch(err => {
                            console.error('Update error:', err);
                            this.value = oldValue;
                            showToast('خطأ في الاتصال بالخادم', 'error');
                        })
                        .finally(() => {
                            this.disabled = false;
                            this.style.backgroundColor = '';
                        });
                    });
                });
            }

            setupEditableFields();
            setupEditableSelects();

            // وظيفة عرض الرسائل
            function showToast(message, type = 'success') {
                const colors = {
                    success: {
                        bg: 'bg-green-100',
                        border: 'border-green-500',
                        text: 'text-green-800'
                    },
                    error: {
                        bg: 'bg-red-100',
                        border: 'border-red-500',
                        text: 'text-red-800'
                    }
                };

                const toast = document.createElement('div');
                toast.className = `
                    fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2
                    z-50 w-[80%] md:w-[400px]
                    ${colors[type].bg} ${colors[type].border} ${colors[type].text}
                    px-6 py-4 rounded-xl shadow-lg text-center text-lg font-semibold
                    animate-fade-in
                `;
                toast.textContent = message;

                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('animate-fade-out');
                    setTimeout(() => toast.remove(), 500);
                }, 4000);
            }

            // جعل showToast متاحة عالمياً
            window.showToast = showToast;

            // تحديث حالة الأزرار عند التحميل
            updateToggleButton();
            updateDeleteBtnVisibility();
        });
    </script>

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

        .box-header .flex > div {
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
</section>
@endsection