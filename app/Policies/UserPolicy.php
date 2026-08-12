<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Validation\Rules\Can;

class UserPolicy
{
    private function typeSlug(int $userTypeId): string
    {
        return match ($userTypeId) {
            1 => 'Admin',
            2 => 'Student',
            3 => 'Employee',
            default => 'User',
        };
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('Index User');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Create User');
    }

    public function viewAnyOfType(User $user, int $userTypeId): bool
    {
        $typeSlug = $this->typeSlug($userTypeId);
        return $user->can("Index " . ucfirst($typeSlug));
    }
    public function createOfType(User $user, int $userTypeId): bool
    {
        $typeSlug = $this->typeSlug($userTypeId);
        return $user->can("Create " . ucfirst($typeSlug));
    }
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->can('Show '. ucfirst($this->typeSlug($model->user_type_id))) || $user->id === $model->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->can('Edit '. ucfirst($this->typeSlug($model->user_type_id))) || $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->can('Delete '. ucfirst($this->typeSlug($model->user_type_id))) && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
