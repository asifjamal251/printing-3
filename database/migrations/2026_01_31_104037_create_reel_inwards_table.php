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
        Schema::create('reel_inwards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->string('vendor_id')->nullable();
            $table->string('receipt_no');
            $table->date('bill_date')->nullable();
            $table->string('bill_number')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('status_id')->default(3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reel_inwards');
    }
};
