<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'view dashboard',
            'view transactions',
            'create transactions',
            'edit transactions',
            'delete transactions',
            'view rabs',
            'create rabs',
            'edit rabs',
            'delete rabs',
            'view invoices',
            'create invoices',
            'delete invoices',
            'view payables',
            'create payables',
            'delete payables',
            'access trash',
            'restore trash',
            'force delete trash',
            'manage users',
            'manage accounts',
            'manage categories',
            'manage locations',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 1. Super Admin
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->syncPermissions(Permission::all());

        // 2. Manager
        $manager = Role::firstOrCreate(['name' => 'Manager']);
        $manager->syncPermissions([
            'view dashboard',
            'view transactions',
            'create transactions',
            'edit transactions',
            'delete transactions',
            'view rabs',
            'create rabs',
            'edit rabs',
            'delete rabs',
            'view invoices',
            'create invoices',
            'delete invoices',
            'view payables',
            'create payables',
            'delete payables',
            'access trash',
            'restore trash',
            'manage accounts',
            'manage categories',
            'manage locations',
        ]);

        // 3. Staff (Data Entry)
        $staff = Role::firstOrCreate(['name' => 'Staff']);
        $staff->syncPermissions([
            'view dashboard',
            'view transactions',
            'create transactions',
            'view rabs',
            'create rabs',
            'view invoices',
            'view payables',
        ]);

        // Assign Role to Existing Users
        $adminUsers = User::whereIn('email', ['admin@shoeworkshop.com', 'admin@example.com'])->get();
        /** @var User $user */
        foreach ($adminUsers as $user) {
            $user->assignRole($superAdmin);
        }

        // If no specifically named admin exists, assign to first user
        if ($adminUsers->isEmpty()) {
            /** @var User|null $firstUser */
            $firstUser = User::first();
            if ($firstUser) {
                $firstUser->assignRole($superAdmin);
            }
        }
    }
}
