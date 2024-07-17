<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Создание роли SuperUser
        $superUserRole = Role::create(['name' => 'super-user']);

        // Создание пользователя Admin и назначение роли
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@mail.ru',
            'password' => bcrypt('admin4'),
            'phone' => '+79999999999'
        ]);

        $admin->assignRole($superUserRole);

        // Создание роли User
        Role::create(['name' => 'user']);

        // Создание роли User
        Role::create(['name' => 'guest']);
    }
}

