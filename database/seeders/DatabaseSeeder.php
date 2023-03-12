<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\DadosEmpresa;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RegrasAcessoSeeder::class,
            UsersSeeder::class,
            DadosEmpresaSeeder::class,
            FuncaoSeeder::class
        ]);
    }
}
