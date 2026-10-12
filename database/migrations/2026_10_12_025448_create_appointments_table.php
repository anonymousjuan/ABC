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
    Schema::create('appointments', function (Blueprint $table) {
        $table->id();

        // Clinic and patient
        $table->foreignId('clinic_id')
            ->constrained('clinics')
            ->restrictOnDelete();

        $table->foreignId('patient_id')
            ->constrained('patients')
            ->restrictOnDelete();

        // Assigned doctor (optional until doctor accounts are implemented)
        $table->foreignId('doctor_id')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        // Appointment details
        $table->string('appointment_number')->unique();
        $table->date('appointment_date');
        $table->time('start_time');
        $table->time('end_time')->nullable();

        $table->string('appointment_type')->default('consultation');
        $table->string('status')->default('scheduled');

        $table->text('reason')->nullable();
        $table->text('notes')->nullable();

        // Staff member who created the appointment
        $table->foreignId('created_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        // Optional link to the resulting consultation
        $table->foreignId('encounter_id')
            ->nullable()
            ->constrained('encounters')
            ->nullOnDelete();

        $table->timestamps();

        $table->index(['clinic_id', 'appointment_date', 'status']);
        $table->index(['patient_id', 'appointment_date']);
        $table->index(['doctor_id', 'appointment_date']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
