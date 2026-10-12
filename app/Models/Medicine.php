<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medicine extends Model
{
     protected $fillable = [
        'clinic_id',
        'medicine_code',
        'generic_name',
        'brand_name',
        'strength',
        'dosage_form',
        'unit_of_measure',
        'requires_prescription',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'requires_prescription' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}
