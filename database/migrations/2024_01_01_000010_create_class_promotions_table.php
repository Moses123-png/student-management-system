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
        Schema::create('class_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('from_class_id')->constrained('classes')->onDelete('restrict');
            $table->foreignId('to_class_id')->constrained('classes')->onDelete('restrict');
            $table->year('academic_year');
            $table->timestamp('promotion_date');
            $table->foreignId('promoted_by')->constrained('users')->onDelete('restrict');
            $table->enum('status', ['Promoted', 'Held Back', 'Graduated'])->default('Promoted');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('student_id');
            $table->index('academic_year');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_promotions');
    }
};
