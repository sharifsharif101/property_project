<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
         public function up(): void
    {
        // نعدل عمود enum بإزالة 'vacant' و 'rented' وإضافة 'ready_for_rent' وترك الخيارات الأخرى كما هي
        DB::statement("ALTER TABLE units MODIFY COLUMN status ENUM('under_maintenance', 'under_renovation', 'ready_for_rent') NOT NULL DEFAULT 'ready_for_rent'");
    }

    public function down(): void
    {
        // نعيد العمود للوضع السابق
        DB::statement("ALTER TABLE units MODIFY COLUMN status ENUM('vacant', 'rented', 'under_maintenance', 'under_renovation') NOT NULL DEFAULT 'vacant'");
    }
    
};
