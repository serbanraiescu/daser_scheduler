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
        Schema::table('clients', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->after('email');
            $table->integer('no_show_count')->default(0)->after('birth_date');
            $table->timestamp('last_reactivation_sent_at')->nullable()->after('fidelity_card_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['birth_date', 'no_show_count', 'last_reactivation_sent_at']);
        });
    }
};
