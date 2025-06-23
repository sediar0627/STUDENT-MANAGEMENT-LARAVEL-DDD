<?php

namespace Src\Api\Courses\Infrastructure\Policies;

use App\Enum\PermissionCase;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return PermissionCase::VIEW_COURSE->checkPermission($user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, string $id): bool
    {
        return PermissionCase::VIEW_COURSE->checkPermission($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return PermissionCase::CREATE_COURSE->checkPermission($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, string $id): bool
    {
        return PermissionCase::EDIT_COURSE->checkPermission($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, string $id): bool
    {
        return PermissionCase::DELETE_COURSE->checkPermission($user);
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
