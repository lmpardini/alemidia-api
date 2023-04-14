<?php

namespace Database\Seeders;

use App\Models\Produto;
use Carbon\Carbon;
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
        $produtoArray = [
            [
                "nome" => "Totem",
                "slug" => Str::slug('Totem', '_'),
                "descricao" => "Totem fotográfico com impressão de fotos 10x15 com a quantidade de fotos e pelo tempo mencionado nos detalhes do produto",
                "quantidade_dia" => 3,
            ],
            [
                "nome" => "Cabine",
                "slug" => Str::slug('Cabine', '_'),
                "descricao" => "Cabine Inflável com impressão de fotos 10x15 com a quantidade de fotos e pelo tempo mencionado nos detalhes do produto",
                "quantidade_dia" => 1,
            ],
            [
                "nome" => "Espelho Magico",
                "slug" => Str::slug('Espelho Magico', '_'),
                "descricao" => "Espelho Magico com impressão de fotos 10x15 com a quantidade de fotos e periodo mencionado nos detalhes do produto",
                "quantidade_dia" => 1,
            ],
            [
                "nome" => "Robo Led",
                "slug" => Str::slug('Robo Led', '_'),
                "descricao" => "Robo Led dançarino com acessorios e período mencionado nos detalhes do produto",
                "quantidade_dia" => 1,
            ],
            [
                "nome" => "Plataforma 360",
                "slug" => Str::slug('Plataforma 360', '_'),
                "descricao" => "Plataforma 360º com captura de video e envio para celular do convidado durante o período período mencionado nos detalhes do produto",
                "quantidade_dia" => 1,
            ],
        ];

        foreach ($produtoArray as $produto) {

            $novoProduto = new Produto();
            $novoProduto->nome = $produto['nome'];
            $novoProduto->slug = $produto['slug'];
            $novoProduto->descricao = $produto['descricao'];
            $novoProduto->quantidade_dia = $produto['quantidade_dia'];
            $novoProduto->save();
        }
    }
}
