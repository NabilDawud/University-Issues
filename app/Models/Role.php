<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
 public function userTypes()
    {
        return $this->belongsToMany(UserType::class, 'role_user_type', 'role_id', 'user_type_id');
    }
}
