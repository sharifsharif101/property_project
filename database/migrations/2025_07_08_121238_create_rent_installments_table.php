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
        Schema::create('rent_installments', function (Blueprint $table) {
                $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade'); // إذا حُذف العقد، تُحذف أقساطه
            $table->date('due_date'); // تاريخ الاستحقاق
            $table->decimal('amount_due', 10, 2); // المبلغ المستحق
            $table->decimal('late_fee', 10, 2)->default(0.00); // رسوم التأخير
            $table->decimal('amount_paid', 10, 2)->default(0.00); // المبلغ المدفوع
            $table->enum('status', ['Due', 'Paid', 'Partially Paid', 'Overdue', 'Cancelled'])->default('Due');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_installments');
    }
};
