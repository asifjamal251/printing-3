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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('added_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('bill_to')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('ship_to')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('firm_id')->nullable()->constrained('firms')->nullOnDelete();
            $table->date('bill_date')->nullable();
            $table->string('bill_number')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->string('transporter_name')->nullable();
            $table->unsignedInteger('status_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
