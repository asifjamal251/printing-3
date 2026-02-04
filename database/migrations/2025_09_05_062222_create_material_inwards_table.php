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
        Schema::create('material_inwards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_order_id')->constrained('material_orders')->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->string('bill_no', 191);
            $table->string('receipt_no');
            $table->date('bill_date');
            $table->string('subtotal')->nullable();
            $table->string('gst_total')->nullable();
            $table->string('total')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('status_id')->default(5)->constrained('statuses')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_inwards');
    }
};
