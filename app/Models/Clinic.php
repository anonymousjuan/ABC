<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clinic extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'logo',
        'status',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}