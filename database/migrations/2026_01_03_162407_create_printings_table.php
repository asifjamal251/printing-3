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
        Schema::create('printings', function (Blueprint $table) {
            $table->id();
             $table->foreignId('job_card_id')->constrained()->onDelete('cascade');
             $table->foreignId('admin_id')->nullable()->constrained('admins');
             $table->foreignId('operator_id')->nullable()->constrained('operators');
             $table->foreignId('job_card_stage_id')->constrained()->onDelete('cascade');
             $table->string('counter')->nullable();
             $table->dateTime('completed_at')->nullable();
             $table->foreignId('completed_by')->nullable()->constrained('admins');
             $table->unsignedInteger('status_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printings');
    }
};
