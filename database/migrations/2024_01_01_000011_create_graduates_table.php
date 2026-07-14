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
        Schema::create('graduates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->unique()->constrained('students')->onDelete('cascade');
            $table->year('graduation_year');
            $table->date('graduation_date');
            $table->string('final_class');
            $table->enum('achievement_level', ['Excellent', 'Good', 'Average', 'Below Average'])->nullable();
            $table->boolean('scholarship_received')->default(false);
            $table->text('notes')->nullable();
            $table->string('diploma_path')->nullable();
            $table->timestamps();

            $table->index('graduation_year');
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduates');
    }
};
