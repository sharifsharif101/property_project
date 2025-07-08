@extends('layouts.app')

@section('title', 'تعديل الوحدة')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">تعديل الوحدة #{{ $unit->id }}</h3>
        </div>
        
        <div class="card-body">
            <form action="{{ route('units.update', $unit->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- معلومات أساسية -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="unit_number" class="font-weight-bold">رقم الوحدة</label>
                            <input type="text" name="unit_number" id="unit_number" 
                                   class="form-control @error('unit_number') is-invalid @enderror" 
                                   value="{{ old('unit_number', $unit->unit_number) }}" required>
                            @error('unit_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="property_id" class="font-weight-bold">العقار التابع له</label>
                            <select name="property_id" id="property_id" class="form-control @error('property_id') is-invalid @enderror" required>
                                <option value="">اختر العقار</option>
                                @foreach($properties as $property)
                                    <option value="{{ $property->property_id }}" {{ $unit->property_id == $property->property_id ? 'selected' : '' }}>
                                        {{ $property->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('property_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="floor_number" class="font-weight-bold">رقم الطابق</label>
                            <input type="number" name="floor_number" id="floor_number" 
                                   class="form-control @error('floor_number') is-invalid @enderror" 
                                   value="{{ old('floor_number', $unit->floor_number) }}" required>
                            @error('floor_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- المواصفات -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bedrooms" class="font-weight-bold">عدد غرف النوم</label>
                            <input type="number" name="bedrooms" id="bedrooms" 
                                   class="form-control @error('bedrooms') is-invalid @enderror" 
                                   value="{{ old('bedrooms', $unit->bedrooms) }}" required min="0">
                            @error('bedrooms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="bathrooms" class="font-weight-bold">عدد الحمامات</label>
                            <input type="number" name="bathrooms" id="bathrooms" 
                                   class="form-control @error('bathrooms') is-invalid @enderror" 
                                   value="{{ old('bathrooms', $unit->bathrooms) }}" required min="0">
                            @error('bathrooms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="area" class="font-weight-bold">المساحة (م²)</label>
                            <input type="number" step="0.01" name="area" id="area" 
                                   class="form-control @error('area') is-invalid @enderror" 
                                   value="{{ old('area', $unit->area) }}" required min="0">
                            @error('area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- الحالة والتفاصيل الإضافية -->
                <div class="row mt-3">
                    <div class="col-md-6">
                 <div class="form-group">
    <label for="status" class="font-weight-bold">حالة الوحدة</label>
    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
        <option value="">اختر الحالة</option>
        <option value="available" {{ $unit->status == 'available' ? 'selected' : '' }}>متاحة</option>
        <option value="reserved" {{ $unit->status == 'reserved' ? 'selected' : '' }}>محجوزة</option>
        <option value="rented" {{ $unit->status == 'rented' ? 'selected' : '' }}>مؤجرة</option>
        <option value="under_maintenance" {{ $unit->status == 'under_maintenance' ? 'selected' : '' }}>تحت الصيانة</option>
        <option value="disabled" {{ $unit->status == 'disabled' ? 'selected' : '' }}>معطلة</option>
    </select>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                    </div>
                </div>

                <!-- ملاحظات -->
                <div class="form-group mt-3">
                    <label for="notes" class="font-weight-bold">ملاحظات</label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $unit->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- أزرار التحكم -->
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save"></i> حفظ التعديلات
                    </button>
                    <a href="{{ route('units.index') }}" class="btn btn-secondary px-4">
                        <i class="fas fa-arrow-left"></i> إلغاء والعودة
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection