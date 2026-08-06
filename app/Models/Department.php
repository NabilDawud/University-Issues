<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'email',
        'extension_number',
        'office_number',
        'is_active',
        'description',
        'deanship_id',
    ];
    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean'
    ];

    public function getTransNameAttribute()
    {
        return $this->name[app()->getLocale()] ?? $this->name['en'];
    }
    public function deanship()
    {
        return $this->belongsTo(Deanship::class)->withDefault();
    }
    public function majors()
    {
        return $this->hasMany(Major::class);
    }
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
