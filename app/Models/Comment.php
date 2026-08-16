<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable('issue_id', 'user_id', 'comment')]
class Comment extends Model
{
    public function issue()
    {
        return $this->belongsTo(Issue::class)->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
