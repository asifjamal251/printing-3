<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('items')->nullOnDelete();
            
            $table->foreignId('coating_type_id')->nullable()->constrained('coating_types')->nullOnDelete();
            $table->foreignId('other_coating_type_id')->nullable()->constrained('other_coating_types')->nullOnDelete();
            
            $table->foreignId('product_type_id')->nullable()->constrained('product_types')->nullOnDelete();

            $table->string('item_name')->nullable();
            $table->string('item_size')->nullable();
            $table->enum('batch', ['Yes', 'No'])->nullable();
            $table->string('colour')->nullable();
            $table->string('gsm')->nullable();
            $table->enum('embossing', ['Yes', 'No'])->nullable();
            $table->enum('leafing', ['Yes', 'No'])->nullable();
            $table->enum('back_print', ['Yes', 'No'])->nullable();
            $table->enum('braille', ['Yes', 'No'])->nullable();
            $table->string('artwork_code')->nullable();
            $table->unsignedInteger('quantity')->default(0); 
            $table->string('rate', 50)->default(0); 
            $table->string('gst_percentage', 50)->default(18);
            $table->string('amount', 50)->default(0); 
            $table->string('gst_amount', 50)->default(0); 
            $table->string('total_amount', 50)->default(0); 
            $table->string('remarks', 255)->nullable(); 

            $table->dateTime('completed_at')->nullable();
            $table->unsignedTinyInteger('status_id')->default(14);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};