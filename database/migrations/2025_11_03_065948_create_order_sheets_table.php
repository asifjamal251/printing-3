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
        Schema::create('order_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('designer')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('item_process_details_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_order_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('dye_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sheet_size')->nullable();
            $table->string('ups')->nullable(); 
            $table->string('quantity')->nullable(); 
            $table->string('final_quantity')->nullable(); 
            $table->enum('job_type', ['Mix','Seperate'])->nullable();
            $table->enum('urgent', ['Yes', 'No'])->nullable();
            $table->integer('status_id')->default(1);
            $table->boolean('quantity_status')->default(0);
            $table->boolean('gsm_status')->default(0);
            $table->boolean('ups_status')->default(0);
            $table->boolean('job_type_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_sheets');
    }
};
