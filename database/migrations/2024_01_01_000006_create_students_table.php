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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique(); // STD001, STD002, etc
            $table->string('surname');
            $table->string('other_names');
            $table->enum('gender', ['Male', 'Female', 'Other'])->default('Male');
            $table->date('date_of_birth');
            $table->string('photo_path')->nullable();
            $table->year('entry_year');
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('set null');
            $table->enum('status', ['Active', 'Graduated', 'Dropped Out'])->default('Active');
            $table->foreignId('guardian_id')->nullable()->constrained('guardians')->onDelete('set null');
            $table->foreignId('community_worker_id')->nullable()->constrained('community_workers')->onDelete('set null');
            $table->string('zone')->nullable(); // Nansana East, Nansana West, etc
            $table->timestamps();
            $table->softDeletes(); // For data recovery

            $table->unique('student_id');
            $table->index('class_id');
            $table->index('guardian_id');
            $table->index('status');
            $table->index('entry_year');
            $table->index('zone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
