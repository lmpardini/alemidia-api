<?php

namespace Database\Seeders;

use App\Models\Colaborador;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FuncaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('funcao')->insert(
        [
            [
                "nome_funcao" => "Vendedor",
                "slug" => Str::slug('vendedor', '_'),
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "nome_funcao" => "Monitor",
                "slug" => Str::slug('monitor', '_'),
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
        ]);
    }
}
