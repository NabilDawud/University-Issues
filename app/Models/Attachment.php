<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable('issue_id', 'user_id', 'file_name', 'file_path', 'file_type', 'file_size')]
class Attachment extends Model
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
