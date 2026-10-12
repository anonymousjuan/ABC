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
    Schema::create('encounters', function (Blueprint $table) {
        $table->id();

        // Clinic and patient associations
        $table->foreignId('clinic_id')
            ->constrained('clinics')
            ->restrictOnDelete();

        $table->foreignId('patient_id')
            ->constrained('patients')
            ->restrictOnDelete();

        // Encounter identification
        $table->string('encounter_number')->unique();

        // Visit details
        $table->dateTime('encounter_date');
        $table->string('encounter_type')->default('consultation');
        $table->string('status')->default('open');

        // Clinical information
        $table->text('chief_complaint')->nullable();
        $table->longText('history_of_present_illness')->nullable();
        $table->longText('physical_examination')->nullable();
        $table->longText('assessment')->nullable();
        $table->longText('treatment_plan')->nullable();
        $table->text('follow_up_instructions')->nullable();

        // User who recorded the encounter
        $table->foreignId('created_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->dateTime('closed_at')->nullable();

        $table->timestamps();

        // Efficient retrieval of encounters per patient and clinic
        $table->index(['clinic_id', 'patient_id', 'encounter_date']);
        $table->index(['clinic_id', 'status']);
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encounters');
    }
};
