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
        Schema::create('processings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('added_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('designer')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('attachement')->nullable()->constrained('medias')->nullOnDelete();
            $table->string('processing_number')->nullable();
            $table->integer('status_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processings');
    }
};
