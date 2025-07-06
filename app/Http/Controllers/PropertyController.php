<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;  
use App\Models\Unit; 
use Carbon\Carbon;
class PropertyController extends Controller

{

    public function index()
    {
        $properties = Property::all(); // جلب كل البيانات
        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        return view('properties.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'type' => 'required|in:big_house,building,villa',
            'status' => 'required|in:available,rented,under_maintenance',
        ]);

        Property::create($validated);

        return redirect()->route('properties.create')->with('success', 'تمت إضافة العقار بنجاح.');
    }

    public function edit(Property $property)
    {
        return view('properties.edit', compact('property'));
    }
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',  // صححت الحد الأقصى لأن max:1 خطأ
            'type' => 'required|in:big_house,building,villa',
            'status' => 'required|in:available,rented,under_maintenance',
        ]);

        $property->update($validated);

      return redirect()->route('properties.index')
    ->with('success', 'تم تحديث بيانات العقار بنجاح.');
    }


    public function show($property_id)
    {
        $property = Property::findOrFail($property_id);

        return view('properties.show', compact('property'));
    }

    public function destroy($id)
    {
        $property = Property::findOrFail($id);
        $property->delete();

        return redirect()->route('properties.index')
            ->with('success', 'تم حذف العقار بنجاح.');
    }


public function getUnits($propertyId)
{
    $today = Carbon::today();

    // استعلام للوحدات التي:
    //  - تنتمي للعقار المطلوب
    //  - ولا توجد عقود نشطة أو قادمة لها

    $units = Unit::where('property_id', $propertyId)
        ->whereDoesntHave('contracts', function ($query) use ($today) {
            $query->where('status', 'active')
                  ->orWhere(function ($q) use ($today) {
                      $q->where('start_date', '>', $today)
                        ->whereIn('status', ['active', 'draft']); // ممكن 'draft' أو أي حالة للعقد القادم
                  });
        })
        ->get(['id', 'unit_number']);

    return response()->json($units);
}


}
