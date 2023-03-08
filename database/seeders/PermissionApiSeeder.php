<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $apiPermissions = [
            'RoleCreate',
            'RoleEdit',
            'RoleDelete',
            'RoleShow',
            'PermissionCreate',
            'PermissionEdit',
            'PermissionShow',
            'PermissionDelete',
            'TeacherCreate',
            'TeacherEdit',
            'TeacherShow',
            'TeacherDelete',
            'StudentCreate',
            'StudentEdit',
            'StudentShow',
            'StudentDelete'
        ];
        
        $teacherApiPermissions = [
            'TeacherShow',
            'StudentCreate',
            'StudentEdit',
            'StudentShow',
            'StudentDelete'
        ];

        $studentApiPermissions = ['StudentShow'];


        foreach ($apiPermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'admin-api']);
        }

        foreach ($teacherApiPermissions as $permission) {
            Permission::create(['name' =>  $permission, 'guard_name' => 'teacher-api']);
        }

        foreach ($studentApiPermissions as $permission) {
            Permission::create(['name' =>  $permission, 'guard_name' => 'student-api']);
        }


        $apiRole = Role::create(['name' => 'api', 'guard_name' => 'admin-api']);
        $teacherApiRole = Role::create(['name' => 'teacher_api', 'guard_name' => 'teacher-api']);
        $studentApiRole = Role::create(['name' => 'student_api', 'guard_name' => 'student-api']);


        $apiRole->syncPermissions($apiPermissions);
        $teacherApiRole->syncPermissions($teacherApiPermissions);
        $studentApiRole->syncPermissions($studentApiPermissions);


        $user = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
        ]);
        $user->assignRole($apiRole);

        $teacher = \App\Models\Teacher::factory()->create([
            'name' => 'Teacher',
            'email' => 'teacher@gmail.com',
        ]);
        $teacher->assignRole($teacherApiRole);

        $student = \App\Models\Student::factory()->create([
            'name' => 'Student',
            'email' => 'student@gmail.com',
        ]);

        $student->assignRole($studentApiRole);
    }
}
