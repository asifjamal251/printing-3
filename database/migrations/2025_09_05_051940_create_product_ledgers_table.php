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
        
        Schema::create('product_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_attribute_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->string('financial_year')->nullable();
            $table->string('reference_no')->nullable(); // invoice, purchase order, etc.
            $table->enum('type', ['in', 'out', 'adjustment'])->nullable(); // stock in or stock out
            $table->string('old_quantity', 191)->default(0.00);
            $table->string('new_quantity', 191)->default(0.00);
            $table->string('current_quantity', 191)->default(0.00);
            $table->string('source_type')->nullable(); // model name: Purchase, Sale, etc.
            $table->unsignedBigInteger('source_id')->nullable(); // model id
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_ledgers');
    }
};
