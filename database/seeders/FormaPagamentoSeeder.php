<?php

namespace Database\Seeders;

use App\Models\PagamentoFormaPagamento;
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
                "descricao" => "Pagamento via Pix - Chave: (colocar chave pix aqui)",

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
                "nome"      => "Sem Custo",
                "slug"      => "sem_custo",
                "descricao" => "Contratos fechados sem valor",
            ],
        ];

        foreach ($formasPagamentos as $fp) {
            $formaPagamento = new PagamentoFormaPagamento();
            $formaPagamento->nome = $fp['nome'];
            $formaPagamento->slug = $fp['slug'];
            $formaPagamento->descricao = $fp['descricao'];
            $formaPagamento->save();
        }
    }
}
