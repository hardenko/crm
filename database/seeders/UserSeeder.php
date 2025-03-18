<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'view dashboard']);

        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        Permission::create(['name' => 'view orders']);
        Permission::create(['name' => 'create orders']);
        Permission::create(['name' => 'edit orders']);
        Permission::create(['name' => 'delete orders']);

        Permission::create(['name' => 'view stock_movements']);
        Permission::create(['name' => 'create stock_movements']);

        Permission::create(['name' => 'view warehouse']);

        Permission::create(['name' => 'view client']);
        Permission::create(['name' => 'edit client']);
        Permission::create(['name' => 'create client']);
        Permission::create(['name' => 'delete client']);

        Permission::create(['name' => 'view components']);
        Permission::create(['name' => 'edit components']);
        Permission::create(['name' => 'create components']);
        Permission::create(['name' => 'delete components']);

        Permission::create(['name' => 'view products']);
        Permission::create(['name' => 'edit products']);
        Permission::create(['name' => 'create products']);
        Permission::create(['name' => 'delete products']);


        $role1 = Role::create(['name' => 'admin']);

        $role2 = Role::create(['name' => 'manager']);
        $role2->givePermissionTo('view users',
            'view dashboard',
            'view orders', 'create orders', 'edit orders',
            'view client', 'edit client', 'create client',
            'view products'
        );

        $role3 = Role::create(['name' => 'wh_manager']);
        $role3->givePermissionTo('view stock_movements', 'create stock_movements',
            'view warehouse',
            'view components'
        );

        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'test@example.com',
        ]);
        $user->assignRole($role1);

        $user = User::factory()->create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
        ]);
        $user->assignRole($role2);

        $user = User::factory()->create([
            'name' => 'Wh_manager',
            'email' => 'wh_manager@example.com',
        ]);
        $user->assignRole($role3);
    }
}
