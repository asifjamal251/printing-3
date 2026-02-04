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
        Schema::create('job_card_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_attribute_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('job_card_id')->constrained()->onDelete('cascade');
            $table->string('required_sheet')->nullable();
            $table->string('wastage_sheet')->nullable();
            $table->string('total_sheet')->nullable();
            $table->enum('paper_divide', ['1','2','3','4','5','6'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_card_products');
    }
};
