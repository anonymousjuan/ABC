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
    Schema::create('lab_tests', function (Blueprint $table) {
        $table->id();

        $table->foreignId('clinic_id')
            ->constrained('clinics')
            ->restrictOnDelete();

        $table->string('test_code', 30);
        $table->string('test_name');
        $table->string('category')->nullable();
        $table->string('specimen_type')->nullable();
        $table->text('preparation_instructions')->nullable();
        $table->unsignedInteger('turnaround_time_hours')->nullable();
        $table->decimal('price', 10, 2)->nullable();
        $table->boolean('is_active')->default(true);

        $table->timestamps();

        $table->unique(['clinic_id', 'test_code']);
        $table->index(['clinic_id', 'test_name']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_tests');
    }
};
