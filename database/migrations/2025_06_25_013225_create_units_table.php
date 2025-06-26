<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id(); // المفتاح الأساسي للوحدة

            $table->foreignId('property_id')
                  ->constrained('properties')
                  ->onDelete('cascade')
                  ->onUpdate('cascade')
                  ->name('fk_units_property_id'); // مفتاح خارجي يربط الوحدة بالعقار

            $table->string('unit_number', 20); // رقم الوحدة (مثل A-101, B203)
            $table->unsignedTinyInteger('bedrooms')->nullable(); // عدد غرف النوم (يمكن أن يكون فارغاً إذا لم ينطبق)
            $table->unsignedTinyInteger('bathrooms')->nullable(); // عدد الحمامات (يمكن أن يكون فارغاً إذا لم ينطبق)
            $table->decimal('area', 10, 3)->nullable(); // مساحة الوحدة، تم زيادة الدقة لتشمل 3 أرقام عشرية (مثلاً 120.500)
            $table->unsignedSmallInteger('floor_number')->nullable(); // رقم الطابق الذي تقع فيه الوحدة

            $table->enum('status', ['vacant', 'rented', 'under_maintenance', 'under_renovation'])
                  ->default('vacant'); // حالة الوحدة، مع إضافة خيار "under_renovation"

            // فرض تفرد 'unit_number' لكل 'property_id'
            // هذا يعني أنه لا يمكن أن يكون هناك نفس رقم الوحدة داخل نفس العقار
            $table->unique(['property_id', 'unit_number']);

            $table->index('status'); // فهرس لتحسين البحث بناءً على حالة الوحدة

            $table->timestamps(); // حقلي created_at و updated_at
            $table->softDeletes(); // إضافة soft deletes للاحتفاظ بالوحدات المحذوفة منطقياً
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
