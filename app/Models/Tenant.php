<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Tenant extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

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
    ];

    protected $casts = [
        'id_expiry_date' => 'date',
        'id_verified' => 'boolean',
        'monthly_income' => 'decimal:2',
    ];

    // تكوين Media Collections
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('tenant_images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->singleFile(); // صورة واحدة فقط
    }

    // تكوين Media Conversions للصور المصغرة
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(10)
            ->performOnCollections('tenant_images');

        $this->addMediaConversion('medium')
            ->width(300)
            ->height(300)
            ->performOnCollections('tenant_images');
    }
}