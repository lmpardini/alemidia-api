<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = new User();
        $user->nome = "Administrador do Sistema";
        $user->usuario = "admin";
        $user->email = "software@alemidia.com.br";
        $user->password = bcrypt("admin123");
        $user->primeiro_acesso = false;
        $user->assignRole('admin');
        $user->save();
    }
}
