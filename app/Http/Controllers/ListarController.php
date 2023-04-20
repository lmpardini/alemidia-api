<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use App\Models\Contrato;
use App\Models\PagamentoCondicaoPagamento;
use App\Models\PagamentoFormaPagamento;
use App\Models\Produto;
use Carbon\Carbon;
use Illuminate\Http\Request;


class ListarController extends Controller
{
    public function listarProdutos()
    {
        try {

            $produtos = Produto::where('ativo', true)->get();

            return response()->json(["success" => true, "data" => $produtos], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function listarVendedores(Request $request)
    {
        $this->validate($request, [
            'filtro'=> ''
        ]);

        try {

            $vendedores = Colaborador::select('id', 'nome')
                ->where('ativo', true)
                ->when($request->filtro, function ($query, $filter) {
                    $query->where('nome', 'like', '%'. strtoupper($filter).'%');
                })
                ->whereHas('Funcao', function ($query) {
                    $query->where('slug', 'vendedor');
                })->get();

            return response()->json(["success" => true, "data" => $vendedores], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function listarCondicaoPagamento(Request $request)
    {
        try {

            $condicaoPagamentos = PagamentoCondicaoPagamento::where('ativo', true)->with('PagamentoFormaPagamento')->get();

            return response()->json(["success" => true, "data" => $condicaoPagamentos], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }

    }
    public function listarFormaPagamento()
    {
        try {

            $formaPagamentos = PagamentoFormaPagamento::where('ativo', true)->get();

            return response()->json(["success" => true, "data" => $formaPagamentos], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function listarEventosPorData(Request $request)
    {
        $this->validate($request, [
            'data_inicio' => 'required|date_format:Y-m-d',
            'data_fim'    => 'required|date_format:Y-m-d'
        ]);

        try {

            $retorno = [];

            $dataInicio = Carbon::createFromFormat('Y-m-d', $request->data_inicio);
            $dataFim = Carbon::createFromFormat('Y-m-d', $request->data_fim);

            $contratos = Contrato::select('id', 'data_evento')
                ->whereBetween('data_evento', [$dataInicio, $dataFim])
                ->with('ContratoProduto.Produto:id,nome')
                ->get();

            if ($contratos){
                foreach ($contratos as $contrato) {

                    foreach ($contrato->ContratoProduto as $contratoProduto) {
                        $retorno[]= [
                            'id' => $contrato->id,
                            'title' => $contratoProduto->Produto->nome,
                            'start' => $contrato->data_evento
                        ];
                    }
                }
            }

            return response()->json(["success" => true, "data" => $retorno], 200);

        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }
}
