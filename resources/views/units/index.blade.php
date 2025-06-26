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

                    <table id="example2" class="table table-bordered table-hover table-striped">
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
                            @forelse ($units as $unit)
                                <tr>
                                    <td>{{ $unit->id }}</td>
        <td>{{ $unit->property->name ?? 'عقار #' . $unit->property_id }}</td>
 
                                    <td>{{ $unit->unit_number }}</td>
                                    <td>{{ $unit->bedrooms }}</td>
                                    <td>{{ $unit->bathrooms }}</td>
                                    <td>{{ $unit->area }}</td>
                                    <td>{{ $unit->floor_number }}</td>
                                    <td>
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $statusColors[$unit->status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ $statusLabels[$unit->status] ?? $unit->status }}
                                        </span>
                                    </td>
                                    <td>{{ $unit->created_at?->format('Y-m-d') }}</td>
                                    <td>{{ $unit->updated_at?->format('Y-m-d') }}</td>
                                    <td class="text-center">{{ $unit->deleted_at ? '🗑️' : '' }}</td>
                                    <td>
                                        <a href=" "
                                            class="btn btn-sm btn-info" title="عرض">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href=" "
                                            class="btn btn-sm btn-warning" title="تعديل">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action=""
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="حذف"
                                                onclick="return confirm('هل أنت متأكد من الحذف؟');">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center">لا توجد وحدات حالياً</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
