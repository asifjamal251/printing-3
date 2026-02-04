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
        Schema::create('billing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_for_billing_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_order_item_id')->constrained()->onDelete('cascade');

            $table->foreignId('job_card_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_card_item_id')->constrained()->onDelete('cascade');

            $table->foreignId('product_type_id')->nullable()->constrained('product_types')->nullOnDelete();
            $table->foreignId('coating_type_id')->nullable()->constrained('coating_types')->nullOnDelete();
            $table->foreignId('other_coating_type_id')->nullable()->constrained('other_coating_types')->nullOnDelete();
            $table->string('item_name')->nullable();
            $table->string('item_size')->nullable();
            $table->string('colour')->nullable();
            $table->string('gsm')->nullable();
            $table->enum('embossing', ['Yes', 'No'])->nullable();
            $table->enum('leafing', ['Yes', 'No'])->nullable();
            $table->enum('back_print', ['Yes', 'No'])->nullable();
            $table->enum('braille', ['Yes', 'No'])->nullable();
            $table->string('artwork_code')->nullable();
            
            $table->string('quantity_per_box')->default(0);
            $table->string('number_of_box')->default(0);
            $table->string('total_quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_items');
    }
};
