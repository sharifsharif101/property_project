@extends('layouts.app')

@section('title', 'عرض العقارات')

@section('content')
{{ Breadcrumbs::render('properties.index') }}

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">قائمة العقارات</h3>
                </div>
                <div class="box-body">
                    @if (session('success'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                        x-transition
                        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <strong class="font-bold">!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                    @endif

                    <table id="example2" class=" table table-bordered table-hover table-striped  ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم العقار</th>
                                <th>العنوان</th>
                                <th>النوع</th>
                                <th>الحالة</th>
                                <th>أضيف بتاريخ</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($properties as $property)
                            <tr>
                                <td>{{ $property->property_id }}</td>
                            <td>
    <a href="https://www.google.com" target="_blank">{{ $property->name }}</a>
    </td>
                                <td>{{ $property->address }}</td>
                                <td>
                                    @switch($property->type)
                                    @case('big_house')
                                    بيت كبير
                                    @break

                                    @case('building')
                                    عمارة
                                    @break

                                    @case('villa')
                                    فيلا
                                    @break

                                    @default
                                    -
                                    @endswitch
                                </td>
                                <td>
                                    @php
                                    $badgeStyles = [
                                    'available' => 'bg-green-100 text-green-700',
                                    'rented' => 'bg-yellow-100 text-yellow-700',
                                    'under_maintenance' => 'bg-red-100 text-red-700',
                                    ];

                                    $statusText = [
                                    'available' => 'متاح',
                                    'rented' => 'مؤجر',
                                    'under_maintenance' => 'تحت الصيانة',
                                    ];

                                    $style = $badgeStyles[$property->status] ?? 'bg-gray-100 text-gray-700';
                                    $label = $statusText[$property->status] ?? '-';
                                    @endphp

                                    <span
                                        class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $style }}">
                                        {{ $label }}
                                    </span>
                                </td>

                                <td>{{ $property->created_at->format('Y-m-d') }}</td>
                                <td>

                                    <a href="{{ route('properties.show', $property->property_id) }}"
                                        class="btn btn-sm btn-info" title="عرض">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('properties.edit', $property->property_id) }}"
                                        class="btn btn-sm btn-warning" title="تعديل">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('properties.destroy', $property->property_id) }}"
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
                                <td colspan="7" class="text-center">لا توجد عقارات حالياً</td>
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