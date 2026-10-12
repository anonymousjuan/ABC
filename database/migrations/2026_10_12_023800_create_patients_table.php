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
    Schema::create('patients', function (Blueprint $table) {
        $table->id();

        // Clinic association
        $table->foreignId('clinic_id')
            ->constrained('clinics')
            ->cascadeOnDelete();

        // Patient identification
        $table->string('patient_number')->unique();
        $table->string('first_name');
        $table->string('middle_name')->nullable();
        $table->string('last_name');
        $table->string('suffix')->nullable();

        // Personal information
        $table->date('date_of_birth')->nullable();
        $table->string('sex', 20)->nullable();
        $table->string('civil_status', 30)->nullable();
        $table->string('blood_type', 5)->nullable();

        // Contact information
        $table->string('phone', 30)->nullable();
        $table->string('email')->nullable();
        $table->text('address')->nullable();

        // Emergency contact
        $table->string('emergency_contact_name')->nullable();
        $table->string('emergency_contact_phone', 30)->nullable();
        $table->string('emergency_contact_relationship')->nullable();

        // Record status
        $table->string('status')->default('active');

        $table->timestamps();

        // Support common clinic-specific patient searches
        $table->index(['clinic_id', 'last_name', 'first_name']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
