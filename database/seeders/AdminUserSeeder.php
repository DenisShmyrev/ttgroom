<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    \App\Models\User::create([
        'name' => 'Lola',
        'email' => 'genliz@mail.ru',
        'password' => bcrypt('notpassword'),
        'role' => 'admin'
    ]);
}
}
