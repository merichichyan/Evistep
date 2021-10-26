<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'surname' => 'Admin',
            'password' => Hash::make("123456"),
            'email' => 'admin@email.com',
            'is_admin' => 1
        ]);
    }
}
