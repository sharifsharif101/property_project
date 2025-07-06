<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'name',
        'type',
        'address',
        'description',
        'status',
    ];

    protected $primaryKey = 'property_id';
public function units()
{
    return $this->hasMany(Unit::class, 'property_id');
}

}
