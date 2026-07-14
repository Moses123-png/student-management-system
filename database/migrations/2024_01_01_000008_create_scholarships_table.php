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
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->boolean('has_scholarship')->default(false);
            $table->string('scholarship_type')->nullable(); // Secondary School, University, Other
            $table->string('sponsor_name')->nullable();
            $table->string('sponsor_contact')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('currency')->default('UGX'); // UGX, USD, EUR, etc
            $table->year('start_year')->nullable();
            $table->year('end_year')->nullable();
            $table->enum('status', ['Active', 'Completed', 'Pending', 'Cancelled'])->default('Active');
            $table->string('certificate_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('student_id');
            $table->index('status');
            $table->index('start_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};
