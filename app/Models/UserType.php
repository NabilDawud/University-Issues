<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

#[Fillable('name')]

class UserType extends Model
{
    /** @use HasFactory<\Database\Factories\UserTypeFactory> */
    use HasFactory;
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user_type', 'user_type_id', 'role_id');
    }
}
