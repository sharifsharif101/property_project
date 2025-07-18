<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractRequest extends FormRequest
{
 
    public function authorize(): bool
    {
        return true;
    }

 
   public function rules(): array
{
    $contractId = $this->route('contract')?->id;

    $rules = [
        'tenant_id' => 'required|exists:tenants,id',
        'unit_id' => 'required|exists:units,id',
        'property_id' => 'required|exists:properties,property_id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'rent_amount' => 'required|numeric|min:0.01',
        'rent_type' => 'required|in:daily,weekly,monthly,yearly',
        'security_deposit' => 'nullable|numeric|min:0',
        'reference_number' => 'required|unique:contracts,reference_number,' . $contractId,
        'status' => 'required|in:active,terminated,cancelled,draft',
        'termination_reason' => 'nullable|in:late_payment,property_damage,tenant_request,landlord_request,contract_expiry,other',
        'termination_notes' => 'nullable|string',
    ];

    // إذا الطلب إنشاء عقد (POST method)، الملف مطلوب
    if ($this->isMethod('post')) {
        $rules['contract_file'] = 'required|file|mimes:pdf|max:5120'; // 5MB
    }
    // إذا التعديل (PUT/PATCH) فالملف اختياري
    elseif ($this->isMethod('put') || $this->isMethod('patch')) {
        $rules['contract_file'] = 'nullable|file|mimes:pdf|max:5120';
    }

    return $rules;
}
public function messages(): array
{
    return [
        'contract_file.max' => 'حجم الملف كبير جدًا. يجب ألا يتجاوز 5 ميغابايت.',
        'contract_file.mimes' => 'يجب أن يكون الملف من نوع PDF فقط.',
        'contract_file.required' => 'حقل ملف العقد مطلوب.',
        // يمكنك إضافة أي رسائل مخصصة أخرى هنا
    ];
}
 public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $contractId = $this->route('contract') ? $this->route('contract')->id : null;

        if ($this->unit_id && $this->start_date && $this->end_date) {
            $exists = \App\Models\Contract::where('unit_id', $this->unit_id)
                ->where('status', 'active')
                ->when($contractId, function ($query) use ($contractId) {
                    $query->where('id', '<>', $contractId);
                })
                ->where(function ($query) {
                    $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                          ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                          ->orWhere(function ($q) {
                              $q->where('start_date', '<=', $this->start_date)
                                ->where('end_date', '>=', $this->end_date);
                          });
                })
                ->exists();

            if ($exists) {
                $validator->errors()->add('unit_id', 'هذه الوحدة مؤجرة لفترة تتداخل مع الفترة المختارة.');
            }
                    $status = $this->input('status');
        $reason = $this->input('termination_reason');

        if (in_array($status, ['terminated', 'cancelled']) && empty($reason)) {
            $validator->errors()->add('termination_reason', 'يجب تحديد سبب الإنهاء أو الإلغاء عند تغيير الحالة إلى منتهي أو ملغي');
        }
        }
    });
}


}
