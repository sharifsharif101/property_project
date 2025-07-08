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
        Schema::create('contract_files', function (Blueprint $table) {
                    
            $table->id();

            // ربط الملف بالعقد
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');

            // الاسم الأصلي كما رفعه المستخدم
            $table->string('original_file_name');

            // الاسم الفريد المخزن في القرص
            $table->string('storage_path');

            // معلومات إضافية مفيدة
            $table->string('mime_type', 100);
            $table->unsignedInteger('file_size');
            $table->string('file_hash', 64)->nullable();

            // من قام بالرفع (اختياري لاحقًا)
            $table->unsignedBigInteger('uploaded_by')->nullable();

            $table->timestamps();

            // فهرس لتحسين الاستعلامات
            $table->index('contract_id');

            // مفتاح أجنبي (معلّق حسب وجود users)
            // $table->foreign('uploaded_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_files');
    }
};
