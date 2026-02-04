<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->enum('work_mode', ['personal', 'shared'])->default('personal');
            $table->string('name', 255)->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable()->default(null);
            $table->string('email', 91)->unique();
            $table->string('mobile', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('plain_password', 255)->nullable();
            $table->rememberToken();
            $table->string('avatar')->default('storage/media/1675870540-favicon.png');
            $table->date('date_of_birth')->nullable();
            $table->integer('status_id')->default(14);
            $table->string('google2fa_secret')->nullable();
            $table->integer('google2fa_enabled')->default(15);
            $table->integer('ip_enabled')->default(15);
            $table->integer('login_time_restriction_enabled')->default(15);
            $table->time('login_allowed_from')->nullable();
            $table->time('login_allowed_to')->nullable();
            $table->enum('listing_type', ['Own', 'All'])->nullable()->default('All');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
