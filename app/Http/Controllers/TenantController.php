<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;

class TenantController extends Controller
{
    public function index()
    {
        // تحميل المستأجرين مع الصور
        $tenants = Tenant::with('media')->latest()->paginate(10);
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

        $tenant = Tenant::create($validated);

        // رفع الصورة مع التأكد من اسم الـ collection الصحيح
        if ($request->hasFile('tenant_image')) {
            $tenant->addMediaFromRequest('tenant_image')
                ->toMediaCollection('tenant_images');
        }

        return redirect()->route('tenants.create')->with('success', 'تم حفظ المستأجر بنجاح');
    }
}