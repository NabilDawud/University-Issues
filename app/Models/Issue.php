<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

#[Fillable([
    'requester_number',
    'description',
    'form_data',
    'status',
    'rejection_reason',
    'admin_notes',
    'category_id',
    'major_id',
    'user_id',
    'assigned_to',
    'action_by'
])]
class Issue extends Model
{
    /** @use HasFactory<\Database\Factories\IssueFactory> */
    use HasFactory;
    protected $casts = [
        'form_data' => 'array',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault();
    }
    public function major()
    {
        return $this->belongsTo(Major::class)->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to')->withDefault();
    }
    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by')->withDefault();
    }
    public function assignments()
    {
        return $this->hasMany(IssueAssignment::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
}
