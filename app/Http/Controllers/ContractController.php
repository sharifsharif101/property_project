<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreContractRequest;
use App\Models\Contract;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\ContractFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    // أولاً ننشئ العقد بدون الملف
    $contract = Contract::create($request->validated());

    // بعد إنشاء العقد، نتعامل مع رفع الملف
    if ($request->hasFile('contract_file')) {
        $file = $request->file('contract_file');

        // تحقق إضافي إذا تريد (مثلاً نوع الملف وحجمه)
        // لو تستخدم FormRequest مع validation للملف هذا غير ضروري

        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $fileSize = $file->getSize();
        $uniqueName = (string) \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();

        $file->storeAs('contract_files', $uniqueName);

        // حفظ معلومات الملف في جدول contract_files
        \App\Models\ContractFile::create([
            'contract_id' => $contract->id,
            'original_file_name' => $originalName,
            'storage_path' => 'contract_files/' . $uniqueName,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
        ]);
    } else {
        // الملف مطلوب عند الإنشاء، في حال لم يرفع الملف:
        return redirect()->back()
            ->withInput()
            ->withErrors(['contract_file' => 'ملف العقد مطلوب.']);
    }

    return redirect()->route('contracts.create')->with('success', 'تم إنشاء العقد ورفع الملف بنجاح.');
}




     // عرض نموذج التعديل
 public function edit(Contract $contract)
{
    $tenants = Tenant::all();
    $properties = Property::all();
    $units = Unit::all();

    $contractFile = $contract->contractFiles()->latest()->first();

    return view('contracts.edit', compact('contract', 'tenants', 'properties', 'units', 'contractFile'));
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

    // تحديث بيانات العقد (باستثناء الملف)
    $contract->update($request->validated());

    // معالجة ملف العقد إذا تم رفعه
    if ($request->hasFile('contract_file')) {
        $uploadedFile = $request->file('contract_file');

        // إنشاء اسم ملف فريد
        $fileName = (string) Str::uuid() . '.' . $uploadedFile->getClientOriginalExtension();

        // حفظ الملف في مجلد contract_files داخل storage/app
        $path = $uploadedFile->storeAs('contract_files', $fileName);

        // حذف الملف القديم من التخزين (إذا موجود)
        $oldFile = ContractFile::where('contract_id', $contract->id)->first();
        if ($oldFile) {
            Storage::delete($oldFile->storage_path);
            $oldFile->delete();
        }

        // إضافة سجل ملف جديد في جدول contract_files
        ContractFile::create([
            'contract_id' => $contract->id,
            'original_file_name' => $uploadedFile->getClientOriginalName(),
            'storage_path' => $path,
            'mime_type' => $uploadedFile->getMimeType(),
            'file_size' => $uploadedFile->getSize(),
            'file_hash' => hash_file('sha256', $uploadedFile->getPathname()),
            // 'uploaded_by' => auth()->id(), // إذا تريد تخزين من رفع الملف
        ]);
    }

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

 public function downloadFile(Contract $contract)
{
    // جلب ملف العقد الحالي المرتبط بهذا العقد (مثلاً ملف الإصدار الحالي)
    $contractFile = ContractFile::where('contract_id', $contract->id)
                                ->latest('created_at')
                                ->first();

 
dd($contractFile);

    if (!$contractFile || !Storage::exists($contractFile->storage_path)) {
        abort(404, 'الملف غير موجود.');
    }

    return Storage::download($contractFile->storage_path, $contractFile->original_file_name);
}
}
