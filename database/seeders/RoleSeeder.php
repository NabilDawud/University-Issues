<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
        $role->userTypes()->attach([1]);
        $role = Role::create(['name' => 'Sub Admin', 'guard_name' => 'web']);
        $role->userTypes()->attach([1]);
        $role = Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        $role->userTypes()->attach([1]);

        $role = Role::create(['name' => 'Student', 'guard_name' => 'web']);
        $role->userTypes()->attach([2]);

        $role = Role::create(['name' => 'Dean', 'guard_name' => 'web']);
        $role->userTypes()->attach([3]);
        $role = Role::create(['name' => 'Department Head', 'guard_name' => 'web']);
        $role->userTypes()->attach([3]);
        $role = Role::create(['name' => 'Instructor', 'guard_name' => 'web']);
        $role->userTypes()->attach([3]);
        $role = Role::create(['name' => 'Assistant', 'guard_name' => 'web']);
        $role->userTypes()->attach([2, 3]);

        /*-----------Permissions---------------*/
        Permission::create(['name' => 'Create Deanship', 'guard_name' => 'web']);
        Permission::create(['name' => 'Index Deanship', 'guard_name' => 'web']);
        Permission::create(['name' => 'Show Deanship', 'guard_name' => 'web']);
        Permission::create(['name' => 'Edit Deanship', 'guard_name' => 'web']);
        Permission::create(['name' => 'Delete Deanship', 'guard_name' => 'web']);

        Permission::create(['name' => 'Create Department', 'guard_name' => 'web']);
        Permission::create(['name' => 'Index Department', 'guard_name' => 'web']);
        Permission::create(['name' => 'Show Department', 'guard_name' => 'web']);
        Permission::create(['name' => 'Edit Department', 'guard_name' => 'web']);
        Permission::create(['name' => 'Delete Department', 'guard_name' => 'web']);
        
        Permission::create(['name' => 'Create Major', 'guard_name' => 'web']);
        Permission::create(['name' => 'Index Major', 'guard_name' => 'web']);
        Permission::create(['name' => 'Show Major', 'guard_name' => 'web']);
        Permission::create(['name' => 'Edit Major', 'guard_name' => 'web']);
        Permission::create(['name' => 'Delete Major', 'guard_name' => 'web']);
        
        Permission::create(['name' => 'Create User', 'guard_name' => 'web']);
        Permission::create(['name' => 'Index User', 'guard_name' => 'web']);
        Permission::create(['name' => 'Show User', 'guard_name' => 'web']);
        Permission::create(['name' => 'Edit User', 'guard_name' => 'web']);
        Permission::create(['name' => 'Delete User', 'guard_name' => 'web']);
        
        Permission::create(['name' => 'Create Admin', 'guard_name' => 'web']);
        Permission::create(['name' => 'Index Admin', 'guard_name' => 'web']);
        Permission::create(['name' => 'Show Admin', 'guard_name' => 'web']);
        Permission::create(['name' => 'Edit Admin', 'guard_name' => 'web']);
        Permission::create(['name' => 'Delete Admin', 'guard_name' => 'web']);
        
        Permission::create(['name' => 'Create Student', 'guard_name' => 'web']);
        Permission::create(['name' => 'Index Student', 'guard_name' => 'web']);
        Permission::create(['name' => 'Show Student', 'guard_name' => 'web']);
        Permission::create(['name' => 'Edit Student', 'guard_name' => 'web']);
        Permission::create(['name' => 'Delete Student', 'guard_name' => 'web']);
        
        Permission::create(['name' => 'Create Employee', 'guard_name' => 'web']);
        Permission::create(['name' => 'Index Employee', 'guard_name' => 'web']);
        Permission::create(['name' => 'Show Employee', 'guard_name' => 'web']);
        Permission::create(['name' => 'Edit Employee', 'guard_name' => 'web']);
        Permission::create(['name' => 'Delete Employee', 'guard_name' => 'web']);
        
        Permission::create(['name' => 'Create Role', 'guard_name' => 'web']);
        Permission::create(['name' => 'Index Role', 'guard_name' => 'web']);
        Permission::create(['name' => 'Show Role', 'guard_name' => 'web']);
        Permission::create(['name' => 'Edit Role', 'guard_name' => 'web']);
        Permission::create(['name' => 'Delete Role', 'guard_name' => 'web']);
        
        Permission::create(['name' => 'Create Permission', 'guard_name' => 'web']);
        Permission::create(['name' => 'Index Permission', 'guard_name' => 'web']);
        Permission::create(['name' => 'Show Permission', 'guard_name' => 'web']);
        Permission::create(['name' => 'Edit Permission', 'guard_name' => 'web']);
        Permission::create(['name' => 'Delete Permission', 'guard_name' => 'web']);
        
        Permission::create(['name' => 'Index Role-Permissions', 'guard_name' => 'web']);
        Permission::create(['name' => 'Edit Role-Permissions', 'guard_name' => 'web']);

        

        
    }
}
