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
        Schema::create('material_issues', function (Blueprint $table) {
            $table->id();
            $table->enum('material_issue_type', ['Manual', 'Automatic'])->nullable()->default(null);
            $table->foreignId('create_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->foreignId('department_user_id')->nullable()->constrained('department_users')->onDelete('cascade');
            $table->string('material_issue_number');
            $table->date('material_issue_date');
            $table->unsignedInteger('status_id')->default(1);
            $table->string('remarks', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_issues');
    }
};
