<?php
 use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('restrict');
 
            $table->foreignId('unit_id')->constrained('units')->onDelete('restrict');
            $table->foreignId('property_id')->constrained('properties')->onDelete('restrict');
       

            $table->date('start_date');
            $table->date('end_date');
 
            $table->decimal('rent_amount', 10, 2);
            $table->enum('rent_type', ['daily', 'weekly', 'monthly', 'yearly'])->default('monthly');
            $table->decimal('security_deposit', 10, 2)->default(0);

            $table->string('reference_number')->unique();
            $table->enum('status', ['active', 'terminated', 'cancelled', 'draft'])->default('draft');

            $table->enum('termination_reason', [
                'late_payment',
                'property_damage',
                'tenant_request',
                'landlord_request',
                'contract_expiry',
                'other'
            ])->nullable();

            $table->text('termination_notes')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['property_id', 'status']);
            $table->index(['start_date', 'end_date']);
            $table->index('reference_number');
        });

        // ✅ تنفيذ قيود CHECK بعد إنشاء الجدول تمامًا
        DB::afterCommit(function () {
            DB::statement("ALTER TABLE contracts ADD CONSTRAINT chk_end_after_start CHECK (end_date > start_date)");
            DB::statement("ALTER TABLE contracts ADD CONSTRAINT chk_rent_positive CHECK (rent_amount > 0)");
            DB::statement("ALTER TABLE contracts ADD CONSTRAINT chk_deposit_non_negative CHECK (security_deposit >= 0)");
            DB::statement("ALTER TABLE contracts ADD CONSTRAINT chk_termination_reason_logic CHECK (
                (status IN ('terminated', 'cancelled') AND termination_reason IS NOT NULL) OR
                (status NOT IN ('terminated', 'cancelled') AND termination_reason IS NULL)
            )");
        });
    }

    public function down()
    {
        Schema::dropIfExists('contracts');
    }
};
