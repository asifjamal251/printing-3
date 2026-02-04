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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_type_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();;
            $table->string('name_other')->nullable();
            $table->unsignedInteger('hsn')->nullable();
            $table->unsignedInteger('gsm')->nullable();
            $table->unsignedInteger('gst')->nullable();
            $table->unsignedInteger('media_id')->nullable();
            $table->integer('status_id')->default(14);
            $table->index(['category_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
