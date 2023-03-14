<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PagamentoCondicaoPagamento;
use App\Models\PagamentoFormaPagamento;
use App\Models\PagamentosFormasCondicoes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PagamentoCondicaoPagamentosController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request,[
            'filtro'=> ''
        ]);

        try {

            $condicaoPagamentos = PagamentoCondicaoPagamento::select('id','nome', 'slug', 'ativo')
                ->when($request->filtro, function ($query, $filter) {
                    $query->where('nome', 'like', '%'. strtoupper($filter).'%');
                })->with('PagamentoFormaPagamento')
                ->get();

            return response()->json(["success" => true, "data" => $condicaoPagamentos], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function show(int $id)
    {
        try {

            $condicaoPagamento = PagamentoCondicaoPagamento::whereId($id)->with('PagamentoFormaPagamento')->first();

            if (!$condicaoPagamento){
                throw new \Exception("Condição de pagamento não encontrado");
            }

            $formasPagamento = $condicaoPagamento->PagamentoFormaPagamento->map(function ($item){
                return $item->slug;
            });

            $condicaoPagamento->forma_pagamento = $formasPagamento;
            unset($condicaoPagamento->PagamentoFormaPagamento);

            return response()->json(["success" => true, "data" => $condicaoPagamento], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nome' => 'required|string',
            'ativo'     => 'required|boolean',
            'aceita_parcelamento'     => 'required|boolean',
            'descricao' => 'nullable|string',
            'forma_pagamento' => 'required|array',
            'forma_pagamento*.*' => 'required|exists:pagamento_forma_pagamento,slug'
        ]);

        try {

            DB::beginTransaction();

            /**
             * @var PagamentoCondicaoPagamento $condicaoPagamento
             */
            $condicaoPagamento = PagamentoCondicaoPagamento::where('slug', Str::slug($request->nome, '_'))->first();

            if ($condicaoPagamento) {
                throw new \Exception("Já existe uma condição de pagamento cadastrado com esse nome");
            }

            $condicaoPagamento = new PagamentoCondicaoPagamento();
            $condicaoPagamento->nome = $request->nome;
            $condicaoPagamento->slug = Str::slug($request->nome, '_');
            $condicaoPagamento->descricao = $request->descricao;
            $condicaoPagamento->aceita_parcelamento = $request->aceita_parcelamento;
            $condicaoPagamento->save();

            foreach ($request->forma_pagamento as $formaPgto) {

                /**
                 * @var PagamentoFormaPagamento $formaPgto
                 */
                $formaPgto = PagamentoFormaPagamento::where('slug', $formaPgto)->first();

                if (!$formaPgto){
                    throw new \Exception("Forma de pagamento não encontrada");
                }

                $formaCondicaoPgto = new PagamentosFormasCondicoes();
                $formaCondicaoPgto->condicao_pagamento_id = $condicaoPagamento->id;
                $formaCondicaoPgto->forma_pagamento_id = $formaPgto->id;
                $formaCondicaoPgto->save();
            }

            DB::commit();

            return response()->json(["success" => true, "message" => "Condição de pagamento criado com sucesso"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, int $id)
    {
        $this->validate($request, [
            'nome'      => 'required|string',
            'aceita_parcelamento'     => 'required|boolean',
            'ativo'     => 'required|boolean',
            'descricao' => 'nullable|string',
            'forma_pagamento' => 'required|array',
            'forma_pagamento*.*' => 'required|exists:pagamento_forma_pagamento,slug'
        ]);

        try {

            DB::beginTransaction();

            /**
             * @var PagamentoCondicaoPagamento $condicaoPagamento
             */
            $condicaoPagamento = PagamentoCondicaoPagamento::whereId($id)->first();

            if (!$condicaoPagamento){
                throw new \Exception("Condição de pagamento não encontrado");
            }

            /**
             * @var PagamentosFormasCondicoes $condicaoFormaPagamento
             */
            $condicaoFormaPagamento = PagamentosFormasCondicoes::where('condicao_pagamento_id', $condicaoPagamento->id)->get();

            if ($condicaoFormaPagamento) {
                foreach ($condicaoFormaPagamento as $cFP) {
                    $cFP->delete();
                }
            }

            $condicaoPagamento->nome = $request->nome;
            $condicaoPagamento->slug = Str::slug($request->nome, '_');
            $condicaoPagamento->descricao = $request->descricao;
            $condicaoPagamento->ativo = $request->ativo;
            $condicaoPagamento->aceita_parcelamento = $request->aceita_parcelamento;
            $condicaoPagamento->save();

            foreach ($request->forma_pagamento as $formaPgto) {

                /**
                 * @var PagamentoFormaPagamento $formaPgto
                 */
                $formaPgto = PagamentoFormaPagamento::where('slug', $formaPgto)->first();

                if (!$formaPgto){
                    throw new \Exception("Forma de pagamento não encontrada");
                }

                $formaCondicaoPgto = new PagamentosFormasCondicoes();
                $formaCondicaoPgto->condicao_pagamento_id = $condicaoPagamento->id;
                $formaCondicaoPgto->forma_pagamento_id = $formaPgto->id;
                $formaCondicaoPgto->save();
            }

            DB::commit();

            return response()->json(["success" => true, "message" => "Condição de pagamento editado com sucesso"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }
}
