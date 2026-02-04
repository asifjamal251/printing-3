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
        Schema::create('job_cards', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attachement')->nullable()->constrained('medias')->nullOnDelete();
            $table->string('job_card_number')->nullable();
            $table->enum('job_type', ['Mix','Seperate'])->nullable();

            $table->string('coating_type')->nullable();
            $table->string('other_coating_type')->nullable();
            
            $table->enum('embossing', ['Yes', 'No'])->nullable();
            $table->enum('leafing', ['Yes', 'No'])->nullable();
            $table->enum('printing', ['Online', 'Offline'])->nullable();

            $table->enum('urgent', ['Yes', 'No'])->nullable();
            $table->foreignId('dye_id')->nullable()->constrained()->nullOnDelete();
            $table->string('set_number')->nullable();
            $table->string('sheet_size')->nullable();
            $table->string('required_sheet')->nullable();
            $table->string('wastage_sheet')->nullable();
            $table->string('total_sheet')->nullable();
            $table->enum('paper_divide', ['1','2','3','4','5','6'])->nullable();
            $table->date('tentative_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('status_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_cards');
    }
};
