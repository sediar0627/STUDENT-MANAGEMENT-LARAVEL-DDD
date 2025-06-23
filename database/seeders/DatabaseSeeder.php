<?php

namespace Database\Seeders;

use App\Enum\PermissionCase;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'super_admin@example.com',
        ]);

        $superAdmin->assignRole('super-admin');

        $courseUser = User::factory()->create([
            'name' => 'Course User',
            'email' => 'course_user@example.com',
        ]);

        $courseUser->givePermissionTo([
            PermissionCase::CREATE_STUDENT->value,
            PermissionCase::EDIT_STUDENT->value,
            PermissionCase::DELETE_STUDENT->value,
            PermissionCase::VIEW_STUDENT->value,
        ]);

        $studentUser = User::factory()->create([
            'name' => 'Student User',
            'email' => 'student_user@example.com',
        ]);

        $studentUser->givePermissionTo([
            PermissionCase::CREATE_STUDENT->value,
            PermissionCase::EDIT_STUDENT->value,
            PermissionCase::DELETE_STUDENT->value,
            PermissionCase::VIEW_STUDENT->value,
        ]);
    }
}
