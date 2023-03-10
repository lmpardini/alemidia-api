<?php

namespace Database\Seeders;

use App\Models\FormaPagamento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormaPagamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $formasPagamentos = [

            [
                "nome"      => "Dinheiro",
                "slug"      => "dinheiro",
                "descricao" => "Pagamento em Dinheiro",
            ],
            [
                "nome"      => "Pix",
                "slug"      => "pix",
                "descricao" => "Pagamento via Pix",

            ],
            [
                "nome"      => "Cartão de Crédito",
                "slug"      => "cartao_credito",
                "descricao" => "Pagamento via Cartão de Crédito",
            ],
            [
                "nome"      => "Cartão de Débito",
                "slug"      => "cartao_debito",
                "descricao" => "Pagamento via Cartão de Débito",
            ],
            [
                "nome"      => "Cortesia",
                "slug"      => "cortesia",
                "descricao" => "Contratos fechados sem valor",
            ],
        ];

        foreach ($formasPagamentos as $fp) {
            $formaPagamento = new FormaPagamento();
            $formaPagamento->nome = $fp['nome'];
            $formaPagamento->slug = $fp['slug'];
            $formaPagamento->descricao = $fp['descricao'];
            $formaPagamento->save();
        }
    }
}
