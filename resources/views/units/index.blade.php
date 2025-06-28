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
                <div class="box-header">
                    <h3 class="box-title">قائمة الوحدات</h3>
                </div>
                <div class="box-body">
                    {{-- PHP for status labels --}}
                    @php
                    $statusLabels = [
                    'vacant' => 'شاغرة',
                    'rented' => 'مؤجرة',
                    'under_maintenance' => 'تحت الصيانة',
                    'under_renovation' => 'تحت التجديد',
                    ];
                    @endphp

                    <div class="mb-4 flex justify-end">
                        <button id="toggleViewBtn" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            عرض مجمّع
                        </button>
                    </div>

                    {{-- Full Table View --}}
                    <table id="fullTable" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
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
                            @foreach ($units as $unit)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $unit->property->name }}</td>
                                <td><span class="editable" data-id="{{ $unit->id }}" data-field="unit_number"
                                        contenteditable="true">{{ $unit->unit_number }}</span></td>
                                <td><span class="editable" data-id="{{ $unit->id }}" data-field="bedrooms"
                                        contenteditable="true">{{ $unit->bedrooms }}</span></td>
                                <td><span class="editable" data-id="{{ $unit->id }}" data-field="bathrooms"
                                        contenteditable="true">{{ $unit->bathrooms }}</span></td>
                                <td><span class="editable" data-id="{{ $unit->id }}" data-field="area"
                                        contenteditable="true">{{ $unit->area }}</span></td>
                                <td><span class="editable" data-id="{{ $unit->id }}" data-field="floor_number"
                                        contenteditable="true">{{ $unit->floor_number }}</span></td>
                                <td>
                                    <select class="editable-select" data-id="{{ $unit->id }}" data-field="status">
                                        @foreach ($statusLabels as $key => $label)
                                        <option value="{{ $key }}" {{ $unit->status === $key ? 'selected' : '' }}>{{
                                            $label }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>{{ $unit->created_at->format('Y-m-d') }}</td>
                                <td>{{ $unit->updated_at->format('Y-m-d') }}</td>
                                <td class="text-center">{{ $unit->deleted_at ? 'نعم' : '' }}</td>
                                <td>
                                    {{-- Placeholder for actual view/edit/delete links/forms --}}
                                    <a href="{{ url('/units/' . $unit->id) }}" class="btn btn-sm btn-info"
                                        title="عرض"><i class="fa fa-eye"></i></a>
                                    <a href="{{ url('/units/' . $unit->id . '/edit') }}" class="btn btn-sm btn-warning"
                                        title="تعديل"><i class="fa fa-edit"></i></a>
                                    <form action="{{ url('/units/' . $unit->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="حذف"
                                            onclick="return confirm('هل أنت متأكد من الحذف؟');">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Grouped View --}}
                    <div id="groupedView" class="hidden">
                        @foreach ($groupedUnits as $propertyName => $propertyUnits)
                        <div class="mb-6 border rounded-lg"> {{-- Added border and rounded for better separation --}}
                            <div class="flex items-center justify-between bg-gray-100 p-4 rounded-t-lg">
                                <h3 class="font-bold text-lg">{{ $propertyName }}</h3>
                                <button
                                    class="expand-collapse-btn px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                    عرض الوحدات
                                </button>
                            </div>
                            <div class="units-list hidden">
                                @if ($propertyUnits->isEmpty())
                                <p class="p-4 text-center text-gray-600">لا توجد وحدات متاحة لهذا العقار</p>
                                @else
                                <table class="table table-bordered table-hover table-striped mb-0">
                                    {{-- Added mb-0 to remove extra margin --}}
                                    <thead>
                                        <tr>
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
                                        <tr>
                                            <td><span class="editable" data-id="{{ $unit->id }}"
                                                    data-field="unit_number" contenteditable="true">{{
                                                    $unit->unit_number }}</span>
                                            </td>
                                            <td><span class="editable" data-id="{{ $unit->id }}" data-field="bedrooms"
                                                    contenteditable="true">{{ $unit->bedrooms }}</span>
                                            </td>
                                            <td><span class="editable" data-id="{{ $unit->id }}" data-field="bathrooms"
                                                    contenteditable="true">{{ $unit->bathrooms }}</span>
                                            </td>
                                            <td><span class="editable" data-id="{{ $unit->id }}" data-field="area"
                                                    contenteditable="true">{{ $unit->area }}</span></td>
                                            <td><span class="editable" data-id="{{ $unit->id }}"
                                                    data-field="floor_number" contenteditable="true">{{
                                                    $unit->floor_number }}</span>
                                            </td>
                                            <td>
                                                <select class="editable-select" data-id="{{ $unit->id }}"
                                                    data-field="status">
                                                    @foreach ($statusLabels as $key => $label)
                                                    <option value="{{ $key }}" {{ $unit->status === $key ? 'selected' :
                                                        '' }}>
                                                        {{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>{{ $unit->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                {{-- Placeholder for actual view/edit/delete links/forms --}}
                                                <a href="{{ url('/units/' . $unit->id) }}" class="btn btn-sm btn-info"
                                                    title="عرض"><i class="fa fa-eye"></i></a>
                                                <a href="{{ url('/units/' . $unit->id . '/edit') }}"
                                                    class="btn btn-sm btn-warning" title="تعديل"><i
                                                        class="fa fa-edit"></i></a>
                                                <form action="{{ url('/units/' . $unit->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف"
                                                        onclick="return confirm('هل أنت متأكد من الحذف؟');">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
                const toggleViewBtn = document.getElementById('toggleViewBtn');
                const fullTable = document.getElementById('fullTable');
                const groupedView = document.getElementById('groupedView');

                // اضبط نص الزر حسب الوضع الحالي عند التحميل
                toggleViewBtn.textContent = fullTable.classList.contains('hidden') ? 'عرض كامل' : 'عرض مجمّع';

                toggleViewBtn.addEventListener('click', () => {
                    fullTable.classList.toggle('hidden');
                    groupedView.classList.toggle('hidden');
                    toggleViewBtn.textContent = fullTable.classList.contains('hidden') ? 'عرض كامل' :
                        'عرض مجمّع';
                });

                document.querySelectorAll('.expand-collapse-btn').forEach(button => {
                    button.addEventListener('click', () => {
                        const unitsList = button.closest('.mb-6').querySelector('.units-list');
                        unitsList.classList.toggle('hidden');
                        button.textContent = unitsList.classList.contains('hidden') ? 'عرض الوحدات' :
                            'إخفاء الوحدات';
                    });
                });

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

                document.querySelectorAll('.editable').forEach(el => {
                    el.dataset.old = el.innerText.trim();

                    el.addEventListener('keydown', e => {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            el.blur(); // يحفز الحفظ
                        }
                    });

                    el.addEventListener('blur', () => {
                        const unitId = el.dataset.id;
                        const field = el.dataset.field;
                        let newValue = el.innerText.trim();
                        newValue = stripHTML(newValue);
                        const oldValue = el.dataset.old;

                        if (newValue === oldValue) return;

                        const validationError = validateInput(field, newValue);
                        if (validationError) {
                            showToast(validationError, 'error');
                            el.innerText = oldValue;
                            return;
                        }

                        el.setAttribute('contenteditable', 'false');
                        el.style.backgroundColor = '#fef08a';

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
                                    el.dataset.old = newValue;
                                    showToast(data.success, 'success');
                                } else {
                                    el.innerText = oldValue;
                                    showToast(data.error || 'فشل الحفظ', 'error');
                                }
                            })
                            .catch(() => {
                                el.innerText = oldValue;
                                showToast('خطأ في الاتصال بالخادم', 'error');
                            })
                            .finally(() => {
                                el.setAttribute('contenteditable', 'true');
                                el.style.backgroundColor = '';
                            });
                    });
                });

                document.querySelectorAll('.editable-select').forEach(select => {
                    select.dataset.old = select.value;
                    select.addEventListener('change', () => {
                        const unitId = select.dataset.id;
                        const field = select.dataset.field;
                        const newValue = select.value;
                        const oldValue = select.dataset.old;

                        if (newValue === oldValue) return;

                        select.disabled = true;
                        select.style.backgroundColor = '#fef08a';

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
                                    select.dataset.old = newValue;
                                    showToast(data.success, 'success');
                                } else {
                                    select.value = oldValue;
                                    showToast(data.error || 'فشل الحفظ', 'error');
                                }
                            })
                            .catch(() => {
                                select.value = oldValue;
                                showToast('خطأ في الاتصال  ', 'error');
                            })
                            .finally(() => {
                                select.disabled = false;
                                select.style.backgroundColor = '';
                            });
                    });
                });

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
                        setTimeout(() => toast.remove(), 500); // بعد انتهاء الانيميشن
                    }, 4000);
                }

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
            transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
            overflow: hidden;
            /* Ensure the table within units-list does not have excessive default margins */
            padding-top: 1rem;
            /* Add some padding if desired */
        }

        .units-list.hidden {
            max-height: 0;
            opacity: 0;
            padding-top: 0;
            /* Remove padding when hidden */
        }
    </style>
</section>
@endsection