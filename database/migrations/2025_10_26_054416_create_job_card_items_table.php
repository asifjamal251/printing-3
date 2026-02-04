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
        Schema::create('job_card_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coating_type_id')->nullable()->constrained('coating_types')->nullOnDelete();
            $table->foreignId('other_coating_type_id')->nullable()->constrained('other_coating_types')->nullOnDelete();

            $table->foreignId('job_card_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_process_details_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_order_item_id')->constrained()->onDelete('cascade');
            $table->string('quantity')->nullable(); 
            $table->string('ups')->nullable(); 
            $table->string('rate')->nullable(); 

            $table->string('item_name')->nullable();
            $table->string('item_size')->nullable();
            $table->string('colour')->nullable();
            $table->string('gsm')->nullable();
            $table->enum('embossing', ['Yes', 'No'])->nullable();
            $table->enum('leafing', ['Yes', 'No'])->nullable();
            $table->enum('back_print', ['Yes', 'No'])->nullable();
            $table->enum('braille', ['Yes', 'No'])->nullable();

            $table->integer('status_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_card_items');
    }
};
