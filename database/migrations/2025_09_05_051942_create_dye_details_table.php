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
        
        Schema::create('dye_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dye_id')->constrained('dyes')->cascadeOnDelete();
            $table->foreignId('dye_lock_type_id')->nullable()->constrained('dye_lock_types')->nullOnDelete();
            $table->string('length', 50)->nullable();
            $table->string('width', 50)->nullable();
            $table->string('height', 50)->nullable();
            $table->string('tuckin_flap', 50)->nullable();
            $table->string('pasting_flap', 50)->nullable();
            $table->string('ups', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dye_details');
    }
};
