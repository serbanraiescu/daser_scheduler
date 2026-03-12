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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('status')->default('confirmed'); // confirmed, completed, cancelled, no_show
            $table->string('manage_token')->unique();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'date'], 'bookings_employee_date');
            $table->index('client_id', 'bookings_client');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
