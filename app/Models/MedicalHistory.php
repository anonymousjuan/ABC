<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class MedicalHistory extends Model
{
     protected $fillable = [
        'clinic_id',
        'patient_id',
        'past_medical_conditions',
        'past_surgical_history',
        'family_medical_history',
        'social_history',
        'current_medications',
        'other_notes',
        'recorded_by',
    ];

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
