<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Property;
use Illuminate\Http\Request;

class UnitController extends Controller
{
 


public function updateField(Request $request, Unit $unit)
{
    $field = $request->input('field');
    $value = $request->input('value');

    $rules = [
        'unit_number' => ['string', 'max:20'],
        'bedrooms' => ['integer', 'min:0', 'max:255'],
        'bathrooms' => ['integer', 'min:0', 'max:255'],
        'area' => ['numeric', 'min:0'],
        'floor_number' => ['integer', 'min:0', 'max:65535'],
        'status' => ['in:vacant,rented,under_maintenance,under_renovation'],
    ];

    // منع تعديل الحقول غير المصرح بها
    if (!array_key_exists($field, $rules)) {
        return response()->json(['error' => 'حقل غير صالح'], 422);
    }

    // تحقق من صحة القيمة
    $request->validate([
        'value' => $rules[$field],
    ]);

    try {
        $unit->{$field} = $value;
        $unit->save();

        return response()->json(['success' => 'تم تحديث البيانات بنجاح']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'حدث خطأ أثناء الحفظ.'], 500);
    }
}

  
 
public function index(Request $request)
{
    $units = Unit::with('property')->get();

    // المحددترتيب الوحدات بناءً على الحقل 
    $sortBy = $request->input('sort_by', 'created_at'); // الافتراضي: تاريخ الإضافة
    $sortOrder = $request->input('sort_order', 'desc'); // الافتراضي: الأحدث أولاً

    $groupedUnits = $units->sortBy([
        [$sortBy, $sortOrder]
    ])->groupBy('property.name'); // تجميع الوحدات حسب اسم العقار

    return view('units.index', compact('units', 'groupedUnits'));
}
    // عرض نموذج إنشاء وحدة جديدة
    public function create()
    {
        // جلب كل العقارات لاستخدامها في اختيار العقار للربط بالوحدة
        $properties = Property::all();
        return view('units.create', compact('properties'));
    }

 public function store(Request $request)
{
    $request->validate([
        'property_id' => ['required', 'exists:properties,property_id'],
        'units' => ['required', 'array', 'min:1'],
        'units.*.unit_number' => ['required', 'string', 'max:20'],
        'units.*.bedrooms' => ['required', 'integer', 'min:0', 'max:255'],
        'units.*.bathrooms' => ['required', 'integer', 'min:0', 'max:255'],
        'units.*.area' => ['required', 'numeric', 'min:0'],
        'units.*.floor_number' => ['required', 'integer', 'min:0', 'max:65535'],
        'units.*.status' => ['required', 'in:vacant,rented,under_maintenance,under_renovation'],
    ]);

    $propertyId = $request->input('property_id');

    foreach ($request->input('units') as $unitData) {
        \App\Models\Unit::create([
            'property_id' => $propertyId,
            'unit_number' => $unitData['unit_number'],
            'bedrooms' => $unitData['bedrooms'],
            'bathrooms' => $unitData['bathrooms'],
            'area' => $unitData['area'],
            'floor_number' => $unitData['floor_number'],
            'status' => $unitData['status'],
        ]);
    }

    return redirect()->route('units.index')->with('success', 'تم إضافة الوحدات بنجاح.');
}

}
