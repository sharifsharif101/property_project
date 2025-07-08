<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RentInstallment extends Model
{
    
    use HasFactory;

     protected $fillable = [
        'contract_id',
        'due_date',
        'amount_due',
        'late_fee',
        'amount_paid',
        'status',
        'notes',
    ];

    // كل قسط يتبع لعقد واحد
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    // كل قسط قد يكون له عدة دفعات (في حالة الدفع الجزئي)
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
