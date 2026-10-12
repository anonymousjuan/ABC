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
    Schema::create('prescriptions', function (Blueprint $table) {
        $table->id();

        // Clinic and patient associations
        $table->foreignId('clinic_id')
            ->constrained('clinics')
            ->restrictOnDelete();

        $table->foreignId('patient_id')
            ->constrained('patients')
            ->restrictOnDelete();

        // Related consultation
        $table->foreignId('encounter_id')
            ->constrained('encounters')
            ->restrictOnDelete();

        // Prescribing professional
        $table->foreignId('prescribed_by')
            ->constrained('users')
            ->restrictOnDelete();

        // Prescription identification
        $table->string('prescription_number')->unique();
        $table->dateTime('prescription_date');

        // General instructions and status
        $table->text('instructions')->nullable();
        $table->string('status')->default('active');

        // Optional expiration date
        $table->date('valid_until')->nullable();

        $table->timestamps();

        $table->index(['clinic_id', 'patient_id', 'prescription_date']);
        $table->index(['encounter_id', 'status']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
