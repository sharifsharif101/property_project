<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'property_id',
        'unit_number',
        'bedrooms',
        'bathrooms',
        'area',
        'floor_number',
        'status',
    ];
protected $casts = [
    'status' => 'string',
];
public function property()
{
    return $this->belongsTo(Property::class, 'property_id', 'property_id');
}

// في app/Models/Unit.php
public function contracts()
{
    return $this->hasMany(Contract::class);
}

// عقد نشط فقط
public function activeContract()
{
    return $this->hasOne(Contract::class)->where('status', 'active');
}
}
 
