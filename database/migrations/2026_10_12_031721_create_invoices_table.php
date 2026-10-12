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
    Schema::create('invoices', function (Blueprint $table) {
        $table->id();

        $table->foreignId('clinic_id')
            ->constrained('clinics')
            ->restrictOnDelete();

        $table->foreignId('patient_id')
            ->constrained('patients')
            ->restrictOnDelete();

        $table->foreignId('encounter_id')
            ->nullable()
            ->constrained('encounters')
            ->restrictOnDelete();

        $table->foreignId('created_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->string('invoice_number')->unique();
        $table->date('invoice_date');
        $table->date('due_date')->nullable();

        $table->decimal('subtotal', 12, 2)->default(0);
        $table->decimal('discount_amount', 12, 2)->default(0);
        $table->decimal('tax_amount', 12, 2)->default(0);
        $table->decimal('total_amount', 12, 2)->default(0);

        $table->string('status')->default('draft');
        $table->text('notes')->nullable();

        $table->timestamps();

        $table->index(['clinic_id', 'invoice_date']);
        $table->index(['clinic_id', 'status']);
        $table->index(['patient_id', 'invoice_date']);
    });
}

public function down(): void
{
    Schema::dropIfExists('invoices');
}
};
