<?php

namespace Database\Seeders;

use App\Models\subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        $permissions = [
            'view-clients',
            'create-clients',
            'edit-clients',
            'delete-clients',

            'view-subscriptions',
            'create-subsctiptions',
            'edit-subscriptions',
            'delete-subsctiptions',

            'view-payments',
            'create-payments',
            'edit-payments',
            'delete-payments',

            'export-reports',
            'view-reports',

            'manage-users',
            'manage-roles',
        ];


        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole->syncPermissions($permissions);

        $staffRole->syncPermissions(['view-clients', 'edit-clients', 'view-subsscriptions', 'view-payments', 'export-reports', 'view-reports']);

        $admin = User::where('email', 'admin@example.com')->first();
        if (!$admin) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'status' => 'active',
            ]);
        }

        $user->assignRole('admin');

         $admin = User::where('email', 'staff@example.com')->first();
        if (!$admin) {
            $user = User::create([
                'name' => 'Staff',
                'email' => 'staff@example.com',
                'password' => bcrypt('password'),
                'role' => 'staff',
                'status' => 'active',
            ]);
        }

        $user->assignRole('staff');

         $admin = User::where('email', 'customer@example.com')->first();
        if (!$admin) {
            $user = User::create([
                'name' => 'Customer',
                'email' => 'customer@example.com',
                'password' => bcrypt('password'),
                'role' => 'customer',
                'status' => 'active',
            ]);
        }

        $user->assignRole('customer');
    }
}
