<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Property;
use Illuminate\Http\Request;

class UnitController extends Controller
{

public function index()
{
    $units = Unit::with('property')->get();
    $groupedUnits = $units->sortByDesc('created_at')->groupBy('property.name'); // ترتيب الوحدات حسب الأحدث
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
            'property_id' => 'required|exists:properties,id',
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
