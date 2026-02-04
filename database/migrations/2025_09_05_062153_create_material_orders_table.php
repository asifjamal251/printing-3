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
        Schema::create('material_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('bill_to')->constrained('vendors')->restrictOnDelete();
            $table->foreignId('ship_to')->constrained('vendors')->restrictOnDelete();
            $table->string('order_no');
            $table->date('mo_date');
            $table->string('subtotal')->nullable();
            $table->string('gst_total')->nullable();
            $table->string('total')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('status_id')->default(1)->constrained('statuses')->restrictOnDelete();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_orders');
    }
};
