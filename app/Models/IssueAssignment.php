<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable([
    'issue_id',
    'assigned_to',
    'action_by',
    'status',
    'rejection_reason',
    'admin_notes'
])]
class IssueAssignment extends Model
{
    public function issue()
    {
        return $this->belongsTo(Issue::class)->withDefault();
    }
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to')->withDefault();
    }
    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by')->withDefault();
    }
}
