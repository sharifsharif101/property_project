<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractFile extends Model
{
   protected $fillable = [
        'contract_id',
        'original_file_name',
        'storage_path',
        'mime_type',
        'file_size',
        'file_hash',
        'uploaded_by',
    ];

     public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
