@extends('layouts.app')

@section('title', 'قائمة الوحدات')

@section('content')
 
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">قائمة الوحدات</h3>
                </div>

                <div class="box-body">
                    @if (session('success'))
                        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                            x-transition
                            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">نجاح!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @php
                        $statusLabels = [
                            'vacant' => 'شاغرة',
                            'rented' => 'مؤجرة',
                            'under_maintenance' => 'تحت الصيانة',
                            'under_renovation' => 'تحت التجديد',
                        ];
                        $statusColors = [
                            'vacant' => 'bg-green-100 text-green-700',
                            'rented' => 'bg-yellow-100 text-yellow-700',
                            'under_maintenance' => 'bg-red-100 text-red-700',
                            'under_renovation' => 'bg-purple-100 text-purple-700',
                        ];
                    @endphp

 

 <!-- زر التحويل العلوي -->
<div class="mb-4 flex justify-end">
    <button id="toggleViewBtn" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
        عرض مجمّع
    </button>
</div>

<!-- الجدول الكامل -->
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
            <td>{{ $unit->unit_number }}</td>
            <td>{{ $unit->bedrooms }}</td>
            <td>{{ $unit->bathrooms }}</td>
            <td>{{ $unit->area }}</td>
            <td>{{ $unit->floor_number }}</td>
            <td>{{ $unit->status }}</td>
            <td>{{ $unit->created_at->format('Y-m-d') }}</td>
            <td>{{ $unit->updated_at->format('Y-m-d') }}</td>
            <td class="text-center">{{ $unit->deleted_at ? 'نعم' : '' }}</td>
            <td>
                <!-- العمليات (عرض، تعديل، حذف) -->
                <a href="" class="btn btn-sm btn-info" title="عرض">
                    <i class="fa fa-eye"></i>
                </a>
                <a href=" #" class="btn btn-sm btn-warning" title="تعديل">
                    <i class="fa fa-edit"></i>
                </a>
                <form action="#" method="POST" style="display:inline;">
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

<!-- العرض المجــمّع -->
<div id="groupedView" class="hidden">
    @foreach ($groupedUnits as $propertyName => $propertyUnits)
    <div class="mb-6">
        <div class="flex items-center justify-between bg-gray-100 p-4 rounded-t-lg">
            <h3 class="font-bold text-lg">{{ $propertyName }}</h3>
            <button class="expand-collapse-btn px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                عرض الوحدات
            </button>
        </div>
        <div class="units-list hidden">
            @if ($propertyUnits->isEmpty())
            <p class="p-4 text-center text-gray-600">لا توجد وحدات متاحة لهذا العقار</p>
            @else
            <table class="table table-bordered table-hover table-striped">
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
                        <td>{{ $unit->unit_number }}</td>
                        <td>{{ $unit->bedrooms }}</td>
                        <td>{{ $unit->bathrooms }}</td>
                        <td>{{ $unit->area }}</td>
                      <td>{{ $unit->floor_number }}</td>
            <td>{{ $unit->status }}</td>    
                        <td>{{ $unit->created_at->format('Y-m-d') }}</td>
                        <td>
                            <!-- العمليات (عرض، تعديل، حذف) -->
                            <a href="" class="btn btn-sm btn-info" title="عرض">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="" class="btn btn-sm btn-warning" title="تعديل">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="" method="POST" style="display:inline;">
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

    // Toggle between full and grouped views
    toggleViewBtn.addEventListener('click', () => {
        if (fullTable.classList.contains('hidden')) {
            fullTable.classList.remove('hidden');
            groupedView.classList.add('hidden');
            toggleViewBtn.textContent = 'عرض مجمّع';
        } else {
            fullTable.classList.add('hidden');
            groupedView.classList.remove('hidden');
            toggleViewBtn.textContent = 'عرض كامل';
        }
    });

    // Expand/Collapse units for each property
    document.querySelectorAll('.expand-collapse-btn').forEach(button => {
        button.addEventListener('click', () => {
            const unitsList = button.closest('.mb-6').querySelector('.units-list');
            unitsList.classList.toggle('hidden');
            button.textContent = unitsList.classList.contains('hidden') ? 'عرض الوحدات' : 'إخفاء الوحدات';
        });
    });
});
</script>   

 
    <style>
    .hidden {
        display: none;
    }
    .units-list {
        transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
        overflow: hidden;
    }
    .units-list.hidden {
        max-height: 0;
        opacity: 0;
    }
 
</style>
</section>
@endsection
