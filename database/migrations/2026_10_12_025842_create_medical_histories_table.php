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
    Schema::create('medical_histories', function (Blueprint $table) {
        $table->id();

        // Clinic and patient associations
        $table->foreignId('clinic_id')
            ->constrained('clinics')
            ->restrictOnDelete();

        $table->foreignId('patient_id')
            ->constrained('patients')
            ->restrictOnDelete();

        // Relevant medical background
        $table->longText('past_medical_conditions')->nullable();
        $table->longText('past_surgical_history')->nullable();
        $table->longText('family_medical_history')->nullable();
        $table->longText('social_history')->nullable();
        $table->longText('current_medications')->nullable();
        $table->longText('other_notes')->nullable();

        // Record maintenance
        $table->foreignId('recorded_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamps();

        // A patient has one consolidated medical history per clinic
        $table->unique(['clinic_id', 'patient_id']);
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_histories');
    }
};
