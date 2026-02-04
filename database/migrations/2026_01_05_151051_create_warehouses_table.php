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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mkdt_by')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('mfg_by')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_order_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_card_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_card_item_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('status_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
