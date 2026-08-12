<?php

namespace App\Policies;

use App\Models\Deanship;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DeanshipPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('Index Deanship');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Deanship $deanship): bool
    {
        return $user->can('Show Deanship') || $user->id === $deanship->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Create Deanship');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Deanship $deanship): bool
    {
        return $user->can('Update Deanship') || $user->id === $deanship->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Deanship $deanship): bool
    {
        return $user->can('Delete Deanship') && $user->id !== $deanship->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Deanship $deanship): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Deanship $deanship): bool
    {
        return false;
    }
}
