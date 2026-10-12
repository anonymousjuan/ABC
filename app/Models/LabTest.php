<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabTest extends Model
{
    protected $fillable = [
        'clinic_id',
        'test_code',
        'test_name',
        'category',
        'specimen_type',
        'preparation_instructions',
        'turnaround_time_hours',
        'price',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'turnaround_time_hours' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}
