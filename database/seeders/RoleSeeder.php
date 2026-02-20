<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->warn(PHP_EOL.'Creating Roles and Permissions...');

        // Define Permissions
        $permissions = [
            'access-admin-panel', 'access-artist-panel',
            'view_customers', 'view_customer', 'create_customer', 'update_customer', 'delete_customer',
            'view_users', 'view_user', 'create_user', 'update_user', 'delete_user',
            'view_roles', 'view_role', 'create_role', 'update_role', 'delete_role',
            'view_permissions', 'view_permission', 'create_permission', 'update_permission', 'delete_permission',
            'view_audit_logs',
        ];

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate(['name' => $permission]);
        }

        // Define Roles with Permissions
        $roles = [
            'Admin' => $permissions,
            'Artist' => [
                'access-artist-panel',
            ],
            'Member' => [],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::query()->firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($perms);
        }

        $this->command->info('Roles and permissions seeded successfully.');
    }
}
