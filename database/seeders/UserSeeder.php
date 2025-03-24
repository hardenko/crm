<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $role1 = Role::create(['name' => 'admin']);
        $role2 = Role::create(['name' => 'manager']);
        $role3 = Role::create(['name' => 'wh_manager']);

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
