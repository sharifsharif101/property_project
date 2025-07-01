<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
 use Illuminate\Support\Facades\Storage;

 
class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'father_name',
        'phone',
        'alternate_phone',
        'email',
        'whatsapp',
        'id_type',
        'id_number',
        'id_expiry_date',
        'id_verified',
        'address',
        'employer',
        'monthly_income',
        'notes',
        'tenant_type',
        'status',
        'image_path', // حقل جديد لحفظ مسار الصورة
    ];

    protected $casts = [
        'id_expiry_date' => 'date',
        'id_verified' => 'boolean',
        'monthly_income' => 'decimal:2',
    ];

    // الحصول على رابط الصورة
    public function getImageUrlAttribute()
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return Storage::disk('public')->url($this->image_path);
        }
        return null;
    }
 

    // التحقق من وجود صورة
    public function hasImage()
    {
        return $this->image_path && Storage::disk('public')->exists($this->image_path);
    }

    // حذف الصورة القديمة
    public function deleteOldImage()
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            Storage::disk('public')->delete($this->image_path);
        }
    }
}
