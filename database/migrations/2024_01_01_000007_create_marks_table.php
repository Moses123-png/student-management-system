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
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('subject'); // Mathematics, English, Science, etc
            $table->year('academic_year');
            $table->tinyInteger('term'); // 1, 2, or 3
            $table->integer('test_1_score')->nullable(); // 0-100
            $table->integer('test_2_score')->nullable(); // 0-100
            $table->integer('assignment_score')->nullable(); // 0-100
            $table->integer('exam_score')->nullable(); // 0-100
            $table->decimal('total_score', 5, 2)->nullable(); // Calculated
            $table->char('grade', 1)->nullable(); // A, B, C, D, E, F
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->unique(['student_id', 'subject', 'academic_year', 'term']);
            $table->index('student_id');
            $table->index('academic_year');
            $table->index('term');
            $table->index('grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marks');
    }
};
