<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
 

 
 
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::latest()->paginate(10);
        return view('tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('tenants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'father_name' => 'nullable|string|max:100',
            'phone' => 'required|string',
            'alternate_phone' => 'nullable|string',
            'email' => 'nullable|email',
            'whatsapp' => 'nullable|string',
            'id_type' => 'required|in:national_card,passport,residence',
            'id_number' => 'required|string|unique:tenants,id_number',
            'id_expiry_date' => 'nullable|date',
            'id_verified' => 'boolean',
            'address' => 'nullable|string',
            'employer' => 'nullable|string',
            'monthly_income' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'tenant_type' => 'required|in:individual,company',
            'status' => 'required|in:active,suspended,terminated',
            'tenant_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // رفع الصورة
        if ($request->hasFile('tenant_image')) {
            $image = $request->file('tenant_image');
            
            // إنشاء اسم فريد للصورة
            $imageName = 'tenant_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // مسار حفظ الصورة
            $imagePath = 'tenants/' . $imageName;
            
            // حفظ الصورة
            $image->storeAs('tenants', $imageName, 'public');
            
            // إضافة مسار الصورة للبيانات المعتمدة
            $validated['image_path'] = $imagePath;
        }

        $tenant = Tenant::create($validated);

        return redirect()->route('tenants.index')->with('success', 'تم حفظ المستأجر بنجاح');
    }

    public function show(Tenant $tenant)
    {
        return view('tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        return view('tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'father_name' => 'nullable|string|max:100',
            'phone' => 'required|string',
            'alternate_phone' => 'nullable|string',
            'email' => 'nullable|email',
            'whatsapp' => 'nullable|string',
            'id_type' => 'required|in:national_card,passport,residence',
            'id_number' => 'required|string|unique:tenants,id_number,' . $tenant->id,
            'id_expiry_date' => 'nullable|date',
            'id_verified' => 'boolean',
            'address' => 'nullable|string',
            'employer' => 'nullable|string',
            'monthly_income' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'tenant_type' => 'required|in:individual,company',
            'status' => 'required|in:active,suspended,terminated',
            'tenant_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // رفع الصورة الجديدة
        if ($request->hasFile('tenant_image')) {
            // حذف الصورة القديمة
            $tenant->deleteOldImage();
            
            $image = $request->file('tenant_image');
            $imageName = 'tenant_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = 'tenants/' . $imageName;
            
            $image->storeAs('tenants', $imageName, 'public');
            $validated['image_path'] = $imagePath;
        }

        $tenant->update($validated);

        return redirect()->route('tenants.index')->with('success', 'تم تحديث المستأجر بنجاح');
    }

    public function destroy(Tenant $tenant)
    {
        try {
            // حذف الصورة
            $tenant->deleteOldImage();
            
            // حذف المستأجر
            $tenant->delete();
            
            return redirect()->route('tenants.index')->with('success', 'تم حذف المستأجر بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حذف المستأجر');
        }
    }
}