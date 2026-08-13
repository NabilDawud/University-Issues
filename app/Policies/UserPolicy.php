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
        if ($user->id === $model->id) {
            return true;
        }
        // if (!$user->can('Show ' . ucfirst($this->typeSlug($model->user_type_id)))) {
        //     return false;
        // }
        if ($model->user_type_id === 2) {
            return $this->canAccessStudent($user, $model);
        }
        if ($model->user_type_id === 3) {
            return $this->canAccessEmployee($user, $model);
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return true;
        }
        if (!$user->can('Edit ' . ucfirst($this->typeSlug($model->user_type_id)))) {
            return false;
        }
        if ($model->user_type_id === 2) {
            return $this->canAccessStudent($user, $model);
        }
        if ($model->user_type_id === 3) {
            return $this->canAccessEmployee($user, $model);
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return false;
        }
        if (!$user->can('Delete ' . ucfirst($this->typeSlug($model->user_type_id)))) {
            return false;
        }
        if ($model->user_type_id === 2) {
            return $this->canAccessStudent($user, $model);
        }
        if ($model->user_type_id === 3) {
            return $this->canAccessEmployee($user, $model);
        }
        return true;
    }
    private function canAccessEmployee(User $user, User $employeeUser): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->hasRole('Dean')) {
            return $user->employee?->department?->deanship_id === $employeeUser->employee?->department?->deanship_id;
        }
        if ($user->hasRole('Department Head')) {
            return ($user->employee?->department_id === $employeeUser->employee?->department_id) && (!$employeeUser->hasRole('Dean'));
        }
        return false;
    }

    private function canAccessStudent(User $user, User $studentUser): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->hasRole('Dean')) {
            return $user->employee?->department?->deanship_id === $studentUser->student?->major?->department?->deanship_id;
        }
        if ($user->hasRole('Department Head') || $user->hasRole('Instructor') || $user->hasRole('Assistant')) {
            return $user->employee?->department_id === $studentUser->student?->major?->department_id;
        }
        return false;
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
