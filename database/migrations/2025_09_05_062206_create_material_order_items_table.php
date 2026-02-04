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
        Schema::create('material_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_order_id')->constrained('material_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('product_attribute_id')->constrained('product_attributes')->cascadeOnDelete();
            $table->string('quantity', 191)->nullable();
            $table->string('total_weight', 191)->nullable();
            $table->string('rate', 191)->nullable();
            $table->string('gst', 191)->nullable();
            $table->string('gst_amount', 191);
            $table->string('amount', 191)->nullable();
            $table->integer('status_id')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_order_items');
    }
};
