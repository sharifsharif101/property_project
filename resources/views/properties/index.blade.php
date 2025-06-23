@extends('layouts.app')

@section('title', 'عرض العقارات')

@section('content')
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">قائمة العقارات</h3>
        </div>
        <div class="box-body">
          <table id="example2" class="table table-bordered table-hover">
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
                <td>{{ $property->name }}</td>
                <td>{{ $property->address }}</td>
                <td>
                  @switch($property->type)
                    @case('big_house') بيت كبير @break
                    @case('building') عمارة @break
                    @case('villa') فيلا @break
                    @default - 
                  @endswitch
                </td>
                <td>
                  @switch($property->status)
                    @case('available') متاح @break
                    @case('rented') مؤجر @break
                    @case('under_maintenance') تحت الصيانة @break
                    @default - 
                  @endswitch
                </td>
                <td>{{ $property->created_at->format('Y-m-d') }}</td>
                <td>
                  <a href="#" class="btn btn-sm btn-info" title="عرض">
                    <i class="fa fa-eye"></i>
                  </a>
                  <a href="{{ route('properties.edit', $property->property_id) }}" class="btn btn-sm btn-warning" title="تعديل">
                    <i class="fa fa-edit"></i>
                  </a>
                  <form action="#" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد من الحذف؟');">
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