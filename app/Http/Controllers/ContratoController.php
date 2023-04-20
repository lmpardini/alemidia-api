<?php

namespace App\Http\Controllers;

use App\Models\Assessoria;
use App\Models\Buffet;
use App\Models\Cliente;
use App\Models\Colaborador;
use App\Models\Contrato;
use App\Models\ContratoPagamento;
use App\Models\ContratoProduto;
use App\Models\DadosEmpresa;
use App\Models\PagamentoCondicaoPagamento;
use App\Models\PagamentoFormaPagamento;
use App\Models\Produto;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContratoController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'filtro'=> '',
            'data_inicio'=> '',
            'data_fim'=> ''
        ]);

        try {
            $retorno = [];

            $contratos = Contrato::when($request->filtro, function ($query, $filter)  {
                $query->whereHas('Cliente', function ($query) use ($filter) {
                    $query->where('nome_razao_social', 'like', '%'. strtoupper($filter).'%');
                });
            })->when($request->data_inicio && $request->data_fim , function ($query) use ($request){
                $query->whereBetween('data_evento', [$request->data_inicio, $request->data_fim]);
            })
                ->with(['Cliente','Buffet','ContratoPagamento', 'ContratoProduto.Produto'])->orderBy('data_evento', 'ASC')->get();

            foreach ($contratos as $contrato) {
                $produtos = [];

                foreach ($contrato->ContratoProduto as $ctProd) {
                    /**
                     * @var ContratoProduto $ctProd
                     */
                    $produtos[] = $ctProd->Produto->nome;
                }

                /**
                 * @var Contrato $contrato
                 */
                $retorno[] = [
                    'id'          => $contrato->id,
                    'data_evento' => Carbon::createFromFormat('Y-m-d', $contrato->data_evento)->format('d/m/Y'),
                    'contratante' => $contrato->Cliente->nome_razao_social,
                    'buffet'      => $contrato->Buffet->nome_razao_social,
                    'produtos'    => $produtos
                ];
            }

           return response()->json(["success" => true, "data" => $retorno], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function show(Request $request, int $id)
    {
        try {

            /**
             * @var Contrato $contrato
             */
            $contrato = Contrato::whereId($id)
                ->with('Assessoria', 'Buffet', 'Cliente', 'Vendedor', 'ContratoProduto.Produto', 'ContratoPagamento', 'ContratoPagamento.FormaPagamento', 'CondicaoPagamento')
                ->first();

            if (!$contrato) {
                throw new \Exception("Contrato não encontrado");
            }

            $produtos = [];

            foreach ($contrato->ContratoProduto as $ctProd) {
                /**
                 * @var ContratoProduto $ctProd
                 */
                $produtos[] = $ctProd->Produto->slug;
            }

            $contrato->produtos = $produtos;

            return response()->json(["success" => true, "data" => $contrato], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'data_evento'                             => 'required|date_format:Y-m-d',
            'noivo_debutante'                         => 'required|string|min:3|max:255',
            'cliente*.id'                             => 'required|exists:cliente,id',
            'buffet*.id'                              => 'required|exists:buffet,id',
            'vendedor*.id'                            => 'required|exists:colaborador,id',
            'assessoria*.id'                          => 'required|exists:assessoria,id',
            'hora_inicio'                             => 'nullable',
            'hora_fim'                                => 'nullable',
            'qtde_convidados'                         => 'nullable|numeric',
            'observacao'                              => 'nullable|string|min:3|max:255',
            'observacao_pagamento'                    => 'nullable|string|min:3|max:255',
            'condicao_pgto'                           => 'required|exists:pagamento_condicao_pagamento,slug',
            'pagamento'                               => 'nullable|array',
            'pagamento.*.data_pagamento'              => 'required|date_format:Y-m-d',
            'pagamento.*.valor'                       => 'required|numeric',
            'pagamento.*.forma_pagamento'             => 'required|exists:pagamento_forma_pagamento,slug',
            'produtos_selecionados'                   => 'required|array',
            'produtos_selecionados.*.produto'         => 'required|exists:produto,slug',
            'produtos_selecionados.*.quantidade'      => 'required|numeric',
            'produtos_selecionados.*.quantidade_foto' => 'nullable|numeric',
            'produtos_selecionados.*.impressao'       => 'nullable|string',
            'produtos_selecionados.*.tempo_evento'    => 'required|nullable',
            'produtos_selecionados.*.bazuca'          => 'nullable|boolean',
            'valor_total'                             => 'nullable|numeric',
            'parcelas'                                => 'nullable|numeric',
            'forma_pgto_padrao'                       => 'nullable|string',
        ]);

        try {

            DB::beginTransaction();

            $dataEvento = Carbon::createFromFormat('Y-m-d', $request->data_evento);

            /**
             * @var Cliente $cliente
             */
            $cliente = Cliente::whereId($request->cliente['id'])->first();

            /**
             * @var Buffet $buffet
             */
            $buffet = Buffet::whereId($request->buffet['id'])->first();

            /**
             * @var Assessoria $assessoria
             */
            $assessoria = Assessoria::whereId($request->assessoria['id'])->first();

            /**
             * @var Colaborador $vendedor
             */
            $vendedor = Colaborador::whereId($request->vendedor['id'])->first();

            /**
             * @var PagamentoCondicaoPagamento $condicaoPgto
             */
            $condicaoPgto = PagamentoCondicaoPagamento::where('slug', $request->condicao_pgto)->first();

            $hashVerificacao = md5($request->data_evento.$cliente->id.$buffet->id.$assessoria->id.$vendedor->id);

            $contrato = Contrato::where('hash_verificacao', $hashVerificacao)->first();

            if ($contrato) {
                throw new \Exception("Já existe um contrato cadastrado com os parametros informados", 422);
            }

            $contrato = new Contrato();
            $contrato->data_evento = $dataEvento;
            $contrato->noivo_debutante = $request->noivo_debutante;
            $contrato->cliente_id = $cliente->id;
            $contrato->buffet_id = $buffet->id;
            $contrato->assessoria_id = $assessoria->id;
            $contrato->vendedor_id = $vendedor->id;
            $contrato->hora_inicio = $request->hora_inicio;
            $contrato->hora_fim = $request->hora_fim;
            $contrato->qtde_convidados = $request->qtde_convidados;
            $contrato->condicao_pgto_id = $condicaoPgto->id;
            $contrato->valor_total = $request->valor_total;
            $contrato->observacoes = $request->observacoes;
            $contrato->observacao_pagamento = $request->observacao_pagamento;
            $contrato->parcelas = $request->parcelas;
            $contrato->forma_pgto_padrao = $request->forma_pgto_padrao;
            $contrato->hash_verificacao = $hashVerificacao;
            $contrato->save();

            foreach ($request->pagamento as $pagamento) {

                /**
                 * @var PagamentoFormaPagamento $formaPgto
                 */
                $formaPgto = PagamentoFormaPagamento::where('slug', $pagamento['forma_pagamento'])->first();

                /**
                 * @var Carbon $dataPgto
                 */
                $dataPgto = Carbon::createFromFormat('Y-m-d', $pagamento['data_pagamento']);

                $contratoPagamento = new ContratoPagamento();
                $contratoPagamento->contrato_id = $contrato->id;
                $contratoPagamento->data_pagamento = $dataPgto;
                $contratoPagamento->valor = $pagamento['valor'];
                $contratoPagamento->forma_pagamento_id = $formaPgto->id;
                $contratoPagamento->save();
            }


            foreach ($request->produtos_selecionados as $produtoSelecionado) {

                /**
                 * @var Produto $produto
                 */
                $produto = Produto::where('slug', $produtoSelecionado['produto'])->first();

                $contratoProduto = new ContratoProduto();
                $contratoProduto->contrato_id = $contrato->id;
                $contratoProduto->produto_id = $produto->id;
                $contratoProduto->quantidade = $produtoSelecionado['quantidade'];
                $contratoProduto->quantidade_foto = $produtoSelecionado['quantidade_foto'];
                $contratoProduto->impressao = $produtoSelecionado['impressao'];
                $contratoProduto->tempo_evento = $produtoSelecionado['tempo_evento'];
                $contratoProduto->bazuca = $produtoSelecionado['bazuca'];
                $contratoProduto->save();
            }

            DB::commit();

            return response()->json(["id" => $contrato->id, "success" => true, "message" => "Contrato cadastrada com Sucesso"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, int $id)
    {
        $this->validate($request, [
            'data_evento'                             => 'required|date_format:Y-m-d',
            'noivo_debutante'                         => 'required|string|min:3|max:255',
            'cliente*.id'                             => 'required|exists:cliente,id',
            'buffet*.id'                              => 'required|exists:buffet,id',
            'vendedor*.id'                            => 'required|exists:colaborador,id',
            'assessoria*.id'                          => 'required|exists:assessoria,id',
            'hora_inicio'                             => 'nullable',
            'hora_fim'                                => 'nullable',
            'qtde_convidados'                         => 'nullable|numeric',
            'observacao'                              => 'nullable|string|min:3|max:255',
            'observacao_pagamento'                    => 'nullable|string|min:3|max:255',
            'condicao_pgto'                           => 'required|exists:pagamento_condicao_pagamento,slug',
            'pagamento'                               => 'nullable|array',
            'pagamento.*.data_pagamento'              => 'required|date_format:Y-m-d',
            'pagamento.*.valor'                       => 'required|numeric',
            'pagamento.*.forma_pagamento'             => 'required|exists:pagamento_forma_pagamento,slug',
            'produtos_selecionados'                   => 'required|array',
            'produtos_selecionados.*.produto'         => 'required|exists:produto,slug',
            'produtos_selecionados.*.quantidade'      => 'required|numeric',
            'produtos_selecionados.*.quantidade_foto' => 'nullable|numeric',
            'produtos_selecionados.*.impressao'       => 'nullable|string',
            'produtos_selecionados.*.tempo_evento'    => 'required|nullable',
            'produtos_selecionados.*.bazuca'          => 'nullable|boolean',
            'valor_total'                             => 'nullable|numeric',
            'parcelas'                                => 'nullable|numeric',
            'forma_pgto_padrao'                       => 'nullable|string',
        ]);

        try {

            DB::beginTransaction();

            /**
             * @var Contrato $contrato
             */
            $contrato = Contrato::whereId($id)->with('ContratoProduto', 'ContratoPagamento')->first();

            if (!$contrato) {
                throw new \Exception("Contrato não encontrado");
            }

            if ($contrato->ContratoProduto) {
                foreach ($contrato->ContratoProduto as $cProd) {
                    $cProd->delete();
                }
            }

            if ($contrato->ContratoPagamento) {
                foreach ($contrato->ContratoPagamento as $cPgto) {
                    $cPgto->delete();
                }
            }

            $dataEvento = Carbon::createFromFormat('Y-m-d', $request->data_evento);

            /**
             * @var Cliente $cliente
             */
            $cliente = Cliente::whereId($request->cliente['id'])->first();

            /**
             * @var Buffet $buffet
             */
            $buffet = Buffet::whereId($request->buffet['id'])->first();

            /**
             * @var Assessoria $assessoria
             */
            $assessoria = Assessoria::whereId($request->assessoria['id'])->first();

            /**
             * @var Colaborador $vendedor
             */
            $vendedor = Colaborador::whereId($request->vendedor['id'])->first();

            /**
             * @var PagamentoCondicaoPagamento $condicaoPgto
             */
            $condicaoPgto = PagamentoCondicaoPagamento::where('slug', $request->condicao_pgto)->first();

            $contrato->data_evento = $dataEvento;
            $contrato->noivo_debutante = $request->noivo_debutante;
            $contrato->cliente_id = $cliente->id;
            $contrato->buffet_id = $buffet->id;
            $contrato->assessoria_id = $assessoria->id;
            $contrato->vendedor_id = $vendedor->id;
            $contrato->hora_inicio = $request->hora_inicio;
            $contrato->hora_fim = $request->hora_fim;
            $contrato->qtde_convidados = $request->qtde_convidados;
            $contrato->condicao_pgto_id = $condicaoPgto->id;
            $contrato->valor_total = $request->valor_total;
            $contrato->observacoes = $request->observacoes;
            $contrato->observacao_pagamento = $request->observacao_pagamento;
            $contrato->parcelas = $request->parcelas;
            $contrato->forma_pgto_padrao = $request->forma_pgto_padrao;
            $contrato->save();

            foreach ($request->pagamento as $pagamento) {

                /**
                 * @var PagamentoFormaPagamento $formaPgto
                 */
                $formaPgto = PagamentoFormaPagamento::where('slug', $pagamento['forma_pagamento'])->first();

                /**
                 * @var Carbon $dataPgto
                 */
                $dataPgto = Carbon::createFromFormat('Y-m-d', $pagamento['data_pagamento']);

                $contratoPagamento = new ContratoPagamento();
                $contratoPagamento->contrato_id = $contrato->id;
                $contratoPagamento->data_pagamento = $dataPgto;
                $contratoPagamento->valor = $pagamento['valor'];
                $contratoPagamento->forma_pagamento_id = $formaPgto->id;
                $contratoPagamento->save();
            }


            foreach ($request->produtos_selecionados as $produtoSelecionado) {

                /**
                 * @var Produto $produto
                 */
                $produto = Produto::where('slug', $produtoSelecionado['produto'])->first();

                $contratoProduto = new ContratoProduto();
                $contratoProduto->contrato_id = $contrato->id;
                $contratoProduto->produto_id = $produto->id;
                $contratoProduto->quantidade = $produtoSelecionado['quantidade'];
                $contratoProduto->quantidade_foto = $produtoSelecionado['quantidade_foto'];
                $contratoProduto->impressao = $produtoSelecionado['impressao'];
                $contratoProduto->tempo_evento = $produtoSelecionado['tempo_evento'];
                $contratoProduto->bazuca = $produtoSelecionado['bazuca'];
                $contratoProduto->save();
            }

            DB::commit();

            return response()->json(["success" => true, "message" => "Contrato alterado com sucesso"], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id)
    {
        try {

            DB::beginTransaction();

            /**
             * @var Contrato $contrato
             */
            $contrato = Contrato::whereId($id)->with('ContratoProduto', 'ContratoPagamento')->first();

            if (!$contrato) {
                throw new \Exception("Contrato não encontrado");
            }

            if ($contrato->ContratoProduto) {
                foreach ($contrato->ContratoProduto as $cProd) {
                    $cProd->delete();
                }
            }

            if ($contrato->ContratoPagamento) {
                foreach ($contrato->ContratoPagamento as $cPgto) {
                    $cPgto->delete();
                }
            }

            $contrato->delete();

            DB::commit();

            return response()->json(["success" => true, "message" => "Contrato cancelado com sucesso"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function verificaDisponibilidadeProduto(Request $request)
    {
        $this->validate($request, [
            'id'         => 'nullable',
           'data_evento' => 'required|date_format:Y-m-d',
           'produtos'    => 'required',
           'produtos.*'  => 'required|exists:produto,slug'
        ]);

        try {

            $dataEvento = Carbon::createFromFormat('Y-m-d', $request->data_evento);

            $produtoDisponivel = [];
            $produtosPorDia = [];
            $retorno = [];
            $contagemProdutosPorDia = [];

            foreach ($request->produtos as $prod){

                /**
                 * @var Produto $produto
                 */
                $produto = Produto::where('slug', $prod)->first();

                $produtoDisponivel[][$produto->slug] = $produto->quantidade_dia;
            }

            /**
             * @var Contrato $contratos
             */

            $contratos = Contrato::where('data_evento', $dataEvento->format('Y-m-d'))->get();

            foreach ($contratos as $contrato) {
                foreach ($contrato->ContratoProduto as $contratoProduto) {

                    /**
                     * @var ContratoProduto $contratoProduto
                     * Verifica se o produto que está sendo pesquisado não pertence ao mesmo contrato em caso de edição
                     */
                    if (isset($request->id) && $request->id === $contratoProduto->contrato_id){
                        continue;
                    }

                    $contagemProdutosPorDia[] = $contratoProduto->Produto->slug;
                }
            }

            $produtosPorDia = array_count_values($contagemProdutosPorDia);

            foreach ($produtoDisponivel as $prodDisp) {
               foreach (array_keys($prodDisp) as $t) {
                   if (isset($produtosPorDia[$t]) && $produtosPorDia[$t] >= $prodDisp[$t]){
                       $retorno[] = Produto::select('nome')->where('slug', $t)->first()->nome;
                   }
               }
            }

            if (count($retorno) > 0) {
                return response()->json(["success" => true, "agenda" => true, "data" => $retorno], 200);
            }

            return response()->json(["success" => true, "agenda" => false, "data" => $retorno], 200);

        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function gerarContrato(int $id)
    {
        try {

            /**
             * @var Contrato $contrato
             */
            $contrato = Contrato::whereId($id)
                ->with('Assessoria', 'Buffet', 'Cliente', 'Vendedor', 'ContratoProduto.Produto', 'ContratoPagamento', 'ContratoPagamento.FormaPagamento', 'CondicaoPagamento')
                ->first();

            if (!$contrato) {
                throw new \Exception("Contrato não encontrado");
            }

            /**
             * @var DadosEmpresa $dadosEmpresa
             */
            $dadosEmpresa = DadosEmpresa::whereId(1)->first();

            $pdf = new Dompdf(['enable_remote' =>true, 'isRemoteEnabled', true]);
            $pdf->loadHtml(view('contrato', compact('contrato', 'dadosEmpresa')));
            $pdf->setPaper('A4', 'portrait');
            $pdf->render();
//            $pdf->stream('CT - '.$contrato->id.'.pdf');

            return  response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $contrato->id . '.pdf"',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            ]);

        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function listarDadosResumoEvento(int $id)
    {
        try {

            /**
             * @var Contrato $contrato
             */
            $contrato = Contrato::whereId($id)->with('Cliente', 'Buffet','Assessoria','ContratoProduto')->first();

            $produtos = [];

            $contrato->ContratoProduto->map(function ($item) use(&$produtos) {
                $produtos[] = $item->Produto->nome;

            });

            $retorno = [
                'contrato'         => $contrato->id,
                'data_evento'      => Carbon::createFromFormat('Y-m-d', $contrato->data_evento)->format('d/m/y'),
                'noivo_debutante'  => $contrato->noivo_debutante,
                'contratante'      => $contrato->Cliente->nome_razao_social,
                'assessoria'       => $contrato->Assessoria->nome_razao_social,
                'espaco'           => $contrato->Buffet->nome_razao_social,
                'hora_inicio'      => Carbon::createFromTimeString($contrato->hora_inicio)->format('H:i'),
                'hora_fim'         => Carbon::createFromTimeString($contrato->hora_fim)->format('H:i'),
                'qtde_convidados'  => $contrato->qtde_convidados,
                'produtos'         => $produtos,
                'observacoes'      => $contrato->observacoes,

            ];

            return response()->json(["success" => true, "data" => $retorno], 200);

        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function listarEventosPorDia(Request $request)
    {
        $this->validate($request, [
            'data' => 'required|date_format:Y-m-d'
        ]);

        try {


            $retorno = [];

            /**
             * @var Contrato $contrato
             */
            $contratos = Contrato::where('data_evento', $request->data)
                ->with('Cliente', 'Buffet','Assessoria','ContratoProduto')->get();

            foreach ($contratos as $contrato){

                $produtos = [];

                $contrato->ContratoProduto->map(function ($item) use(&$produtos) {
                    $produtos[] = $item->Produto->nome;

                });

                $retorno[] = [
                    'contrato'         => $contrato->id,
                    'data_evento'      => Carbon::createFromFormat('Y-m-d', $contrato->data_evento)->format('d/m/y'),
                    'noivo_debutante'  => $contrato->noivo_debutante,
                    'contratante'      => $contrato->Cliente->nome_razao_social,
                    'assessoria'       => $contrato->Assessoria->nome_razao_social,
                    'espaco'           => $contrato->Buffet->nome_razao_social,
                    'hora_inicio'      => Carbon::createFromTimeString($contrato->hora_inicio)->format('H:i'),
                    'hora_fim'         => Carbon::createFromTimeString($contrato->hora_fim)->format('H:i'),
                    'qtde_convidados'  => $contrato->qtde_convidados,
                    'produtos'         => $produtos,
                    'observacoes'      => $contrato->observacoes,
                ];

            }

            return response()->json(["success" => true, "data" => $retorno], 200);

        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }
}
