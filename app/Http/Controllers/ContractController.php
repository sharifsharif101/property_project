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
    // توليد رقم مؤقت لعرضه
    do {
        $randomNumber = mt_rand(100000, 999999);
        $referenceNumber = 'REF-' . $randomNumber;
    } while (Contract::where('reference_number', $referenceNumber)->exists());

    return view('contracts.create', compact('referenceNumber'))
        ->with([
            'tenants' => Tenant::all(),
            'units' => Unit::all(),
            'properties' => Property::all(),
        ]);
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
    
   
}
