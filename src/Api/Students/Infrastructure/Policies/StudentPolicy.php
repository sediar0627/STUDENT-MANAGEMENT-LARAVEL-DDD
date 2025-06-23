<?php

namespace Src\Api\Students\Infrastructure\Policies;

use App\Enum\PermissionCase;
use App\Models\User;

class StudentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return PermissionCase::VIEW_STUDENT->checkPermission($user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, string $id): bool
    {
        return PermissionCase::VIEW_STUDENT->checkPermission($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return PermissionCase::CREATE_STUDENT->checkPermission($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, string $id): bool
    {
        return PermissionCase::EDIT_STUDENT->checkPermission($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, string $id): bool
    {
        return PermissionCase::DELETE_STUDENT->checkPermission($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, string $id): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, string $id): bool
    {
        return false;
    }
}
