<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id(); // Creates a BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('app_name', 255)->nullable();
            $table->string('app_tag_line', 255)->nullable();
            $table->text('app_description')->nullable();
            $table->unsignedInteger('logo')->nullable(); // Use unsigned if IDs are foreign keys
            $table->unsignedInteger('favicon')->nullable(); // Same for favicon
            $table->string('email', 191)->nullable();
            $table->string('owner_name', 191)->nullable();
            $table->string('mobile_number', 100)->nullable();
            $table->unsignedInteger('country_id')->nullable(); // Assuming this relates to a foreign key
            $table->unsignedInteger('state_id')->nullable();
            $table->unsignedInteger('district_id')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->unsignedInteger('pincode')->nullable();
            $table->text('address')->nullable();
            $table->dateTime('created_at')->nullable(); // For explicit date and time
            $table->timestamp('updated_at')->nullable(); // For automatic timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_settings');
    }
}
