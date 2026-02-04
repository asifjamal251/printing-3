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
        
        Schema::create('dyes', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['Mix', 'Separate'])->nullable()->default(null);
            $table->string('dye_number')->nullable();
            $table->string('sheet_size')->nullable();
            $table->enum('dye_type', ['Manual', 'Automatic'])->nullable()->default(null);
            $table->integer('status_id')->default(14);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dyes');
    }
};
