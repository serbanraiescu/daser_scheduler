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
        Schema::create('gift_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');
            $table->string('buyer_name')->nullable();
            $table->string('buyer_email')->nullable();
            $table->string('buyer_phone')->nullable();
            $table->decimal('value_amount', 10, 2)->nullable();
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('remaining_uses')->nullable();
            $table->decimal('remaining_value', 10, 2)->nullable();
            $table->date('expires_at')->nullable();
            $table->string('status')->default('active'); // active, redeemed, expired
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_vouchers');
    }
};
