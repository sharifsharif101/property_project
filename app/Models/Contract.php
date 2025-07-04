<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
 
protected $fillable = [
    'tenant_id', 'unit_id', 'property_id',
    'start_date', 'end_date', 'rent_due_date',
    'rent_amount', 'rent_type', 'security_deposit',
    'reference_number', 'status',
    'termination_reason', 'termination_notes',
];
// Contract.php
public function tenant()
{
    return $this->belongsTo(Tenant::class);
}

public function unit()
{
    return $this->belongsTo(Unit::class);
}

public function property()
{
    return $this->belongsTo(Property::class, 'property_id');
}
 protected $casts = [
    'start_date' => 'datetime',
    'end_date' => 'datetime',
];

    
 
}
