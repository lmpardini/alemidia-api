<?php

namespace Database\Seeders;

use App\Models\Produto;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('produtos')->insert(
            [
                [
                    "nome" => "Totem",
                    "slug" => Str::slug('Totem', '_'),
                    "descricao" => "Totem fotográfico com impressão de fotos 10x15 ilimitadas durante o período do evento",
                    "quantidade_dia" => 3,
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now()
                ],
                [
                    "nome" => "Cabine",
                    "slug" => Str::slug('Cabine', '_'),
                    "descricao" => "Cabine Inflável com impressão de fotos 10x15 ilimitadas durante o período do evento",
                    "quantidade_dia" => 1,
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now()
                ],
                [
                    "nome" => "Robo Led",
                    "slug" => Str::slug('Robo Led', '_'),
                    "descricao" => "Robo Led dançarino com Bazuca de CO² durante o periodo de 1h a combinar com o contratante",
                    "quantidade_dia" => 1,
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now()
                ],
                [
                    "nome" => "Plataforma 360",
                    "slug" => Str::slug('Plataforma 360', '_'),
                    "descricao" => "Plataforma 360º com captura de video e envio para celular do convidado durante o período do evento",
                    "quantidade_dia" => 1,
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now()
                ],
            ]);
    }
}
