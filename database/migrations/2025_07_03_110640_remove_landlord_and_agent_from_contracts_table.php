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
        Schema::table('contracts', function (Blueprint $table) {
             // أولاً: حذف القيود الأجنبية إن وجدت
            $table->dropForeign(['landlord_id']);
            $table->dropColumn('landlord_id');

            $table->dropForeign(['agent_id']);
            $table->dropColumn('agent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            //
        });
    }
};
