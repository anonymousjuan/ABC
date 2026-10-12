<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Encounter extends Model
{
     protected $fillable = [
        'clinic_id',
        'patient_id',
        'encounter_number',
        'encounter_date',
        'encounter_type',
        'status',
        'chief_complaint',
        'history_of_present_illness',
        'physical_examination',
        'assessment',
        'treatment_plan',
        'follow_up_instructions',
        'created_by',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'encounter_date' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function vitalSigns(): HasOne
    {
        return $this->hasOne(VitalSign::class);
    }
}
