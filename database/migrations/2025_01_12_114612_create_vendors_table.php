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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained()->onDelete('cascade');
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->string('company_name', 255)->nullable();
            $table->string('email', 91)->unique(); // Make email unique
            $table->string('contact_no', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->rememberToken();
            $table->integer('media_id')->nullable();
            $table->string('gst')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->text('address')->nullable();
            $table->json('cc_emails')->nullable();
            $table->integer('status_id')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
