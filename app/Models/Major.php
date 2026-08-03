<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    /** @use HasFactory<\Database\Factories\MajorFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'degree',
        'is_active',
        'department_id',
    ];
    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean'
    ];
    public function getTransNameAttribute()
    {
        return $this->name[app()->getLocale()] ?? $this->name['en'] ?? '';
    }
    public function department()
    {
        return $this->belongsTo(Department::class)->withDefault();
    }
}
