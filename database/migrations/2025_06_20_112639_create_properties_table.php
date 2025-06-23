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
        Schema::create('properties', function (Blueprint $table) {
            $table->id('property_id'); // المفتاح الأساسي
            $table->string('name'); // اسم العقار
            $table->string('address'); // العنوان
            $table->enum('type', ['big_house', 'building', 'villa']);
            $table->enum('status', ['available', 'rented', 'under_maintenance']); // الحالة
            $table->timestamps(); // created_at و updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
