<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreContractRequest;
use App\Models\Contract;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\Property;
use Illuminate\Http\Request;

class ContractController extends Controller
{

public function index()
{
    $contracts = Contract::with(['tenant', 'unit', 'property'])->latest()->get();

    return view('contracts.index', compact('contracts'));
}
 public function create()
{
    // توليد رقم مرجعي فريد
    do {
        $randomNumber = mt_rand(100000, 999999);
        $referenceNumber = 'REF-' . $randomNumber;
    } while (Contract::where('reference_number', $referenceNumber)->exists());

    // نحتاج فقط إلى المستأجرين والعقارات، أما الوحدات فستُجلب لاحقًا عند اختيار العقار
    $tenants = Tenant::all();
    $properties = Property::all();

    return view('contracts.create', compact('referenceNumber', 'tenants', 'properties'));
}



public function store(StoreContractRequest $request)
{
    
     // تحقق من أن الوحدة تنتمي للعقار المحدد
    $unit = Unit::where('id', $request->unit_id)
                ->where('property_id', $request->property_id)
                ->first();

    if (!$unit) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['unit_id' => 'الوحدة المختارة لا تنتمي للعقار المحدد.']);
    }

    Contract::create($request->validated());
     return redirect()->route('contracts.create')->with('success', 'تم إنشاء العقد بنجاح.');
}
     // عرض نموذج التعديل
public function edit(Contract $contract)
{
    $tenants = Tenant::all();
    $properties = Property::all();
    $units = Unit::all();
    return view('contracts.edit', compact('contract', 'tenants', 'properties', 'units'));
}
    // تحديث بيانات العقد
    public function update(StoreContractRequest $request, Contract $contract)
{
    // تحقق من أن الوحدة تنتمي للعقار المحدد
    $unit = Unit::where('id', $request->unit_id)
                ->where('property_id', $request->property_id)
                ->first();

    if (!$unit) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['unit_id' => 'الوحدة المختارة لا تنتمي للعقار المحدد.']);
    }

    // تحديث بيانات العقد
    $contract->update($request->validated());

    return redirect()->route('contracts.edit', $contract->id)->with('success', 'تم تحديث العقد بنجاح.');
}
public function show(Contract $contract)
{
    return view('contracts.show', compact('contract'));
}
   
public function destroy($id)
{
    $contract = Contract::findOrFail($id);

    // احذف العقد
    $contract->delete();

    // إعادة التوجيه مع رسالة نجاح
    return redirect()->route('contracts.index')
                     ->with('success', 'تم حذف العقد بنجاح.');
}
}
