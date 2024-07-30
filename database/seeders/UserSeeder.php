<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $account = Account::create([
            'inn' => '111111111',
            'ogrn' => '222222222',
            'type' => 'ooo',
            'name' => 'stick-store'
        ]);
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@mail.ru',
            'password' => bcrypt('admin4'),
            'phone' => '+79999999999',
            'account_id' => $account->id
        ]);

        $admin->assignRole( 'super-user');
    }
}

