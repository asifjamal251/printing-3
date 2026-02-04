<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mkdt_by')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('mfg_by')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('product_type_id')->nullable()->constrained('product_types')->nullOnDelete();
            $table->foreignId('coating_type_id')->nullable()->constrained('coating_types')->nullOnDelete();
            $table->foreignId('other_coating_type_id')->nullable()->constrained('other_coating_types')->nullOnDelete();
            $table->string('item_name')->nullable();
            $table->string('item_size')->nullable();
            $table->string('colour')->nullable();
            $table->string('gsm')->nullable();
            $table->enum('embossing', ['Yes', 'No'])->nullable();
            $table->enum('leafing', ['Yes', 'No'])->nullable();
            $table->enum('back_print', ['Yes', 'No'])->nullable();
            $table->enum('braille', ['Yes', 'No'])->nullable();
            $table->string('artwork_code')->nullable();
            $table->unsignedTinyInteger('status_id')->default(14);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};