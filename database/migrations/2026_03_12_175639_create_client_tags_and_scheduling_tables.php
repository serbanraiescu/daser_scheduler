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
        Schema::create('client_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->default('#000000');
            $table->timestamps();
        });

        Schema::create('client_tag_pivot', function (Blueprint $table) {
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('client_tags')->onDelete('cascade');
        });

        Schema::create('employee_schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            $table->timestamps();
        });

        Schema::create('blocked_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_slots');
        Schema::dropIfExists('employee_schedule');
        Schema::dropIfExists('client_tag_pivot');
        Schema::dropIfExists('client_tags');
    }
};
