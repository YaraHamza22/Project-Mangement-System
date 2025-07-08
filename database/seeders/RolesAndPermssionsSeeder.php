<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermssionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

       
        $permissions = [
            'manage teams',
            'manage projects',
            'assign tasks',
            'add comments',
            'view project',
            'delete task',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }


        $admin = Role::firstOrCreate(['name' => 'admin']);
        $manager = Role::firstOrCreate(['name' => 'project_manager']);
        $member = Role::firstOrCreate(['name' => 'member']);


        $admin->syncPermissions($permissions);

        $manager->syncPermissions([
            'manage projects', 'assign tasks', 'add comments', 'view project',
        ]);

        $member->syncPermissions([
            'add comments', 'view project',
        ]);
    }
}
