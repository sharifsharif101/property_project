<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Property;
use Illuminate\Http\Request;

class UnitController extends Controller
{

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

    // حفظ الوحدة الجديدة في قاعدة البيانات
    public function store(Request $request)
    {
        // تحقق من صحة البيانات
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,property_id',
            'unit_number' => 'required|string|max:20',
            'bedrooms' => 'nullable|integer|min:0|max:255',
            'bathrooms' => 'nullable|integer|min:0|max:255',
            'area' => 'nullable|numeric',
            'floor_number' => 'nullable|integer|min:0|max:65535',
            'status' => 'required|in:vacant,rented,under_maintenance,under_renovation',
        ]);

        Unit::create($validated);

        return redirect()->route('units.create')->with('success', 'تم إضافة الوحدة بنجاح');
    }
}
