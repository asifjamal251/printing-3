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
        Schema::create('attachements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_card_id');
            $table->unsignedBigInteger('media_id');
            $table->timestamps();

            // Indexes for optimization
            $table->index('job_card_id');
            $table->index('media_id');

            // Foreign key constraints
            $table->foreign('job_card_id')
                  ->references('id')
                  ->on('job_cards')
                  ->onDelete('cascade');

            $table->foreign('media_id')
                  ->references('id')
                  ->on('medias')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attachements', function (Blueprint $table) {
            $table->dropForeign(['job_card_id']);
            $table->dropForeign(['media_id']);
            $table->dropIndex(['job_card_id']);
            $table->dropIndex(['media_id']);
        });

        Schema::dropIfExists('attachements');
    }
};