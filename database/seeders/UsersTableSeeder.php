<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
        [
            'name' => 'Ramadhan',
            'email' => 'febryramadhan285@gmail.com',
            'password' => Hash::make('Admin@12345'),
            'remember_token' => NULL,
            'created_at' => date('Y-m-d h:i:s'),
            'updated_at' => date('Y-m-d h:i:s')
        ],
        [
            'name' => 'Febri',
            'email' => 'febryramadhan286@gmail.com',
            'password' => Hash::make('Admin@12345'),
            'remember_token' => NULL,
            'created_at' => date('Y-m-d h:i:s'),
            'updated_at' => date('Y-m-d h:i:s')
        ]
        ];

        User::insert($users);

    }
}
