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
        Schema::create('item_process_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');

            $table->foreignId('coating_type_id')->nullable()->constrained('coating_types')->nullOnDelete();
            $table->foreignId('other_coating_type_id')->nullable()->constrained('other_coating_types')->nullOnDelete();

            $table->foreignId('purchase_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('purchase_order_item_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_type_id')->nullable()->constrained('product_types')->nullOnDelete();
            $table->foreignId('designer')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('printing_machine_id')->nullable()->constrained('printing_machines')->nullOnDelete();

            $table->foreignId('dye_id')->nullable()->constrained('dyes')->nullOnDelete();
            $table->foreignId('job_card_id')->nullable()->constrained('job_cards')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();

            $table->string('batch')->nullable();
            
            $table->string('colour')->nullable();
            $table->string('gsm')->nullable(); 
            $table->enum('embossing', ['Yes', 'No'])->nullable();
            $table->enum('leafing', ['Yes', 'No'])->nullable();
            $table->enum('back_print', ['Yes', 'No'])->nullable();
            $table->enum('braille', ['Yes', 'No'])->nullable();
            $table->string('artwork_code')->nullable();

            $table->enum('job_type', ['Mix','Seperate'])->nullable(); 

            $table->string('sheet_size')->nullable(); 
            $table->string('number_of_sheet')->nullable(); 
            $table->string('set_number')->nullable(); 
            $table->string('ups')->nullable(); 
            $table->string('board_size')->nullable(); 
            $table->string('divide')->nullable(); 
            $table->enum('urgent', ['Yes', 'No'])->nullable();

            $table->unsignedInteger('quantity')->default(0); 
            $table->string('rate', 50)->default(0); 
            $table->string('gst_percentage', 50)->default(18);
            $table->string('amount', 50)->default(0); 
            $table->string('gst_amount', 50)->default(0); 
            $table->string('total_amount', 50)->default(0); 
            $table->integer('status_id')->default(1);
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('item_process_details');
    }
};
