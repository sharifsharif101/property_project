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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id(); // المعرف الأساسي للمستأجر

            // معلومات الاسم
            $table->string('first_name', 100)->comment('الاسم الأول للمستأجر (إلزامي)');
$table->string('last_name', 100)->comment('اسم العائلة/اللقب (إلزامي)');
             $table->string('father_name')->nullable()->comment('اسم الأب (اختياري)');
 
            // معلومات الاتصال
            $table->string('phone')->comment('رقم الهاتف الأساسي (إلزامي)');
            $table->string('alternate_phone')->nullable()->comment('رقم هاتف بديل (اختياري)');
            $table->string('email')->nullable()->comment('البريد الإلكتروني (اختياري)');
            $table->string('whatsapp')->nullable()->comment('رقم واتساب (اختياري)');

            // معلومات الهوية
            $table->enum('id_type', ['national_card', 'passport', 'residence'])->default('national_card')
                  ->comment('نوع الهوية: بطاقة وطنية، جواز سفر، أو إقامة');
            $table->string('id_number')->unique()->comment('رقم الهوية (فريد وإلزامي)');
            $table->date('id_expiry_date')->nullable()->comment('تاريخ انتهاء الهوية (اختياري)');
            $table->boolean('id_verified')->default(false)->comment('هل تم التحقق من الهوية؟');

            // معلومات إضافية
            $table->text('address')->nullable()->comment('عنوان السكن (اختياري)');
            $table->string('employer')->nullable()->comment('جهة العمل (اختياري)');
            $table->decimal('monthly_income', 10, 2)->nullable()->comment('الدخل الشهري (اختياري)');
            $table->text('notes')->nullable()->comment('ملاحظات إضافية عن المستأجر (اختياري)');

            // نوع المستأجر وحالته
            $table->enum('tenant_type', ['individual', 'company'])->default('individual')
                  ->comment('نوع المستأجر: فرد أو شركة');
            $table->enum('status', ['active', 'suspended', 'terminated'])->default('active')
                  ->comment('حالة المستأجر: نشط، موقوف، أو منتهي');

            $table->timestamps(); // تواريخ الإنشاء والتحديث

            // ✅ الفهارس لتحسين سرعة البحث
            $table->index('first_name');
            $table->index('last_name');
            $table->index('phone');
            $table->index('email');
            $table->index('status');

            // ✅ فهرس مركب للبحث بالاسم الكامل
            $table->index(['first_name', 'last_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
