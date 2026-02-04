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
        Schema::create('job_card_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_card_id')->constrained()->onDelete('cascade');
             $table->foreignId('operator_id')->nullable()->constrained('operators');
            $table->string('name');     // e.g. PRINTING, LEAFING, BILLING
            $table->string('in_counter')->default(0);
            $table->string('out_counter')->default(0);
            $table->string('status_id')->default(1); // pending / in-progress / done
            $table->integer('order')->nullable(); // stage sequence order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_card_stages');
    }
};
