<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;


class Tenant extends Model implements HasMedia
{
     use InteractsWithMedia;


    /**
     * اسم الجدول المرتبط بالموديل (اختياري إذا الاسم مطابق).
     */
    protected $table = 'tenants';

    /**
     * الحقول القابلة للتعبئة بالجملة.
     */
    protected $fillable = [
        'first_name',
        'father_name',
        'last_name',
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
    ];

    /**
     * الحقول التي يجب تحويلها تلقائيًا لأنواع معينة.
     */
    protected $casts = [
        'id_verified' => 'boolean',
        'id_expiry_date' => 'date',
        'monthly_income' => 'decimal:2',
    ];

        public function registerMediaCollections(): void
    {
        $this->addMediaCollection('tenant_images')->useDisk('public');
    }
 
 public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100)
            ->sharpen(10)
            ->nonQueued(); // لتوليد الصورة المصغرة مباشرة بدون queue
    }

}
