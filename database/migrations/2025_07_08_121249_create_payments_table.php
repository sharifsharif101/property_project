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
        Schema::create('payments', function (Blueprint $table) {
                 $table->id();
            // يُفضل ربط الدفعة بالقسط مباشرةً
            $table->foreignId('rent_installment_id')->constrained('rent_installments')->onDelete('cascade');
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade'); // لتسهيل الاستعلامات
            $table->date('payment_date'); // تاريخ الدفع الفعلي
            $table->decimal('amount', 10, 2); // المبلغ المدفوع
            $table->enum('payment_method', ['Cash', 'Bank Transfer', 'Credit Card', 'Check', 'Online Payment']);
            $table->string('transaction_reference')->nullable(); // رقم مرجع العملية
            $table->text('notes')->nullable();
            // $table->foreignId('received_by')->nullable()->constrained('users'); // إذا كان لديك جدول مستخدمين
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
