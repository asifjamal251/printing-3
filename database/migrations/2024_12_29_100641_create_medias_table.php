<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->string('file', 255)->nullable();
            $table->string('icon', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->string('original_name', 255)->nullable();
            $table->string('size', 255)->nullable();
            $table->string('handle', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medias');
    }
}
