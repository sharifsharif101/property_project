<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Property;
use Illuminate\Http\Request;

class UnitController extends Controller
{

   public function bulkDelete(Request $request)
{
    $ids = $request->input('ids', []);

    if (!is_array($ids) || empty($ids)) {
        return response()->json(['error' => 'لم يتم تحديد وحدات للحذف.'], 400);
    }

    try {
        // التحقق من وجود أي عقود مرتبطة بالوحدات
        $unitsWithContracts = \App\Models\Contract::whereIn('unit_id', $ids)
            ->pluck('unit_id')
            ->toArray();

        if (!empty($unitsWithContracts)) {
            return response()->json([
                'error' => 'لا يمكن حذف بعض الوحدات لأنها مرتبطة بعقود.'
            ], 403);
        }

        // تنفيذ الحذف إذا لم يتم العثور على عقود
        \App\Models\Unit::whereIn('id', $ids)->delete();
        return response()->json(['success' => 'تم حذف الوحدات المحددة بنجاح.']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'فشل في الحذف: ' . $e->getMessage()], 500);
    }
}

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
            'status' => ['required', 'in:ready_for_rent,under_maintenance,under_renovation'],
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
'units.*.status' => ['required', 'in:available,reserved,rented,under_maintenance,disabled'],
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
public function edit(Unit $unit)
{
    // التحقق من العقود المرتبطة
    $hasRestrictedContract = $unit->contracts()->whereIn('status', ['active', 'draft'])->exists();

    if ($hasRestrictedContract) {
        return redirect()->route('units.index')
            ->with('error', 'لا يمكن تعديل الوحدة لأنها مرتبطة بعقد نشط أو مسودة.');
    }

    // جلب جميع العقارات لاستخدامها في النموذج
    $properties = Property::all();

    return view('units.edit', compact('unit', 'properties'));
}
    public function show($id)
    {
        $unit = Unit::findOrFail($id);
        return view('units.show', compact('unit'));
    }
    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $validated = $request->validate([
            'unit_number' => 'required',
            'bedrooms' => 'required|integer',
            'bathrooms' => 'required|integer',
            'area' => 'required|numeric',
            'floor_number' => 'required|integer',
            'status' => 'required', 'in:available,reserved,rented,under_maintenance,disabled',
        ]);

        $unit->update($validated);

        return redirect()->route('units.index')
            ->with('success', 'تم تحديث الوحدة بنجاح');
    }
}
