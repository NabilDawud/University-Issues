<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deanship extends Model
{
    /** @use HasFactory<\Database\Factories\DeanshipFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'email',
        'extension_number',
        'office_number',
        'is_active',
        'description',
    ];

    Protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
    ];

    public function getTransNameAttribute()
    {
        return $this->name[app()->getLocale()] ?? $this->name['en'];
    }
}
