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
    Schema::create('medicines', function (Blueprint $table) {
        $table->id();

        $table->foreignId('clinic_id')
            ->constrained('clinics')
            ->restrictOnDelete();

        $table->string('medicine_code', 50);
        $table->string('generic_name');
        $table->string('brand_name')->nullable();
        $table->string('strength', 100)->nullable();
        $table->string('dosage_form', 100)->nullable();
        $table->string('unit_of_measure', 50)->default('piece');

        $table->boolean('requires_prescription')->default(true);
        $table->boolean('is_active')->default(true);

        $table->text('notes')->nullable();
        $table->timestamps();

        $table->unique(['clinic_id', 'medicine_code']);
        $table->index(['clinic_id', 'generic_name']);
    });
}

public function down(): void
{
    Schema::dropIfExists('medicines');
}
};
