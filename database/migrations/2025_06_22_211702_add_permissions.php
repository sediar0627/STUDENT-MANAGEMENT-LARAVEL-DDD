<?php

use App\Enum\PermissionCase;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            PermissionCase::CREATE_COURSE->value,
            PermissionCase::EDIT_COURSE->value,
            PermissionCase::DELETE_COURSE->value,
            PermissionCase::VIEW_COURSE->value,

            PermissionCase::CREATE_STUDENT->value,
            PermissionCase::EDIT_STUDENT->value,
            PermissionCase::DELETE_STUDENT->value,
            PermissionCase::VIEW_STUDENT->value,
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $superAdmin = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web',
        ]);

        $superAdmin->givePermissionTo(Permission::all());

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
