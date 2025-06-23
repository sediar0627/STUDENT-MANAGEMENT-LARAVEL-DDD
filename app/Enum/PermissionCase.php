<?php

namespace App\Enum;

use App\Models\User;

enum PermissionCase: string
{
    case CREATE_COURSE = 'create-course';
	case EDIT_COURSE = 'edit-course';
	case DELETE_COURSE = 'delete-course';
	case VIEW_COURSE = 'view-course';

	case CREATE_STUDENT = 'create-student';
	case EDIT_STUDENT = 'edit-student';
	case DELETE_STUDENT = 'delete-student';
	case VIEW_STUDENT = 'view-student';

	public function checkPermission(User $user): bool
	{
		return $user->hasPermissionTo($this->value, 'web');
	}
}