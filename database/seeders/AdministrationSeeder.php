<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class AdministrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // USER MODEL
        $userPermission1 = Permission::create(['name' => 'create users']);
        $userPermission2 = Permission::create(['name' => 'read users']);
        $userPermission3 = Permission::create(['name' => 'update users']);
        $userPermission4 = Permission::create(['name' => 'delete users']);

        // ROLE MODEL
        $rolePermission1 = Permission::create(['name' => 'create roles']);
        $rolePermission2 = Permission::create(['name' => 'read roles']);
        $rolePermission3 = Permission::create(['name' => 'update roles']);
        $rolePermission4 = Permission::create(['name' => 'delete roles']);

        // PERMISSION MODEL
        $permission1 = Permission::create(['name' => 'create permissions']);
        $permission2 = Permission::create(['name' => 'read permissions']);
        $permission3 = Permission::create(['name' => 'update permissions']);
        $permission4 = Permission::create(['name' => 'delete permissions']);

        $adminRole = Role::create(['name' => 'admin'])->syncPermissions([
            $userPermission1,
            $userPermission2,
            $userPermission3,
            $userPermission4,
            $rolePermission1,
            $rolePermission2,
            $rolePermission3,
            $rolePermission4,
            $permission1,
            $permission2,
            $permission3,
            $permission4
        ]);

        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.hu',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ])->assignRole($adminRole);
    }
}
