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
       return [
                    'tenant_id' => 'required|exists:tenants,id',
            'unit_id' => 'required|exists:units,id',
            'property_id' => 'required|exists:properties,property_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0.01',
            'rent_type' => 'required|in:daily,weekly,monthly,yearly',
            'security_deposit' => 'nullable|numeric|min:0',
            'reference_number' => 'required|unique:contracts,reference_number',
            'status' => 'required|in:active,terminated,cancelled,draft',
            'termination_reason' => 'nullable|in:late_payment,property_damage,tenant_request,landlord_request,contract_expiry,other',
            'termination_notes' => 'nullable|string',
    ];
    }
public function withValidator($validator)
{
    $validator->after(function ($validator) {
 
        if ($this->unit_id && $this->start_date && $this->end_date) {
            $exists = \App\Models\Contract::where('unit_id', $this->unit_id)
                ->where('status', 'active')
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
        }
    });
}

}
