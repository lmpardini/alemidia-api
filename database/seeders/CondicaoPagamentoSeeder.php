<?php

namespace Database\Seeders;

use App\Models\PagamentoCondicaoPagamento;
use App\Models\PagamentoFormaPagamento;
use App\Models\PagamentosFormasCondicoes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CondicaoPagamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $condicoesPagamento = [
            [
                "nome"                => "A Vista",
                "slug"                => "a_vista",
                "descricao"           => "Condições para pagamento a vista nas formas disponíveis",
                "aceita_parcelamento" => false,
                "formas_pagamentos"   => [1,2]
            ],
            [
                "nome"                => "Parcelado",
                "slug"                => "parcelado",
                "descricao"           => "Condições para pagamento parcelado nas formas disponíveis",
                "aceita_parcelamento" => true,
                "formas_pagamentos"   => [1,2]
            ],
            [
                "nome"                => "Permuta",
                "slug"                => "permuta",
                "descricao"           => "Condições quando o pagamento for em forma de permuta",
                "aceita_parcelamento" => false,
                "formas_pagamentos"   => [5]

            ],
            [
                "nome"                => "Cortesia",
                "slug"                => "cortesia",
                "descricao"           => "Condições quando não houver cobrança do totem ou for um contrato fake",
                "aceita_parcelamento" => false,
                "formas_pagamentos"   => [5]
            ],
            [
                "nome"                => "Contrato Fake",
                "slug"                => "contrato_fake",
                "descricao"           => "Condições quando não houver cobrança do totem ou for um contrato fake",
                "aceita_parcelamento" => false,
                "formas_pagamentos"   => [5]
            ],
        ];

        foreach ($condicoesPagamento as $cp) {

            $condicaoPagamento = new PagamentoCondicaoPagamento();
            $condicaoPagamento->nome = $cp['nome'];
            $condicaoPagamento->slug = $cp['slug'];
            $condicaoPagamento->aceita_parcelamento = $cp['aceita_parcelamento'];
            $condicaoPagamento->descricao = $cp['descricao'];
            $condicaoPagamento->save();

            foreach ($cp['formas_pagamentos'] as $cpFp) {

                $pagamentoFormaCondicao = new PagamentosFormasCondicoes();
                $pagamentoFormaCondicao->condicao_pagamento_id = $condicaoPagamento->id;
                $pagamentoFormaCondicao->forma_pagamento_id = $cpFp;
                $pagamentoFormaCondicao->save();
            }
        }
    }
}
