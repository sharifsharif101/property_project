<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
  
    use HasFactory;

    protected $fillable = [
        'rent_installment_id',
        'contract_id',
        'payment_date',
        'amount',
        'payment_method',
        'transaction_reference',
        'notes',
    ];

    // كل دفعة تخص قسطاً واحداً
    public function rentInstallment()
    {
        return $this->belongsTo(RentInstallment::class);
    }

    // كل دفعة تتبع لعقد واحد
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

     /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'payment_date' => 'date', // أخبر Laravel أن هذا الحقل هو من نوع تاريخ
    ];
 
}
