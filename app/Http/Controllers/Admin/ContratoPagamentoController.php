<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\ContratoPagamento;
use Carbon\Carbon;
use Illuminate\Http\Request;


class ContratoPagamentoController extends Controller
{
    public function listarPagamentos(Request $request)
    {
        $this->validate($request, [
            'tipo_pagamento' => 'required|string',
            'tipo_busca' => 'required|string',
            'periodo' => 'required|string',
            'data_inicio' => 'required|date_format:Y-m-d',
            'data_fim' => 'nullable|date_format:Y-m-d',
        ]);

        try {

            $retorno = [];

            $dataHoje = Carbon::now();

            $contratoPagamentos = ContratoPagamento::when($request->tipo_busca === 'periodo', function ($query) use ($request) {
                $query->when($request->tipo_pagamento === 'em_atraso', function ($query) use ($request) {
                    $query->when($request->periodo === 'hoje', function ($query) use ($request) {
                        $query->where([
                            ['data_pagamento', $request->data_inicio],
                            ['data_pagamento', '<', Carbon::now()->format('Y-m-d')],
                            ['quitado', false],
                        ]);
                    })
                        ->when($request->periodo === 'mes', function ($query) use ($request) {
                            $dataInicio = Carbon::createFromFormat('Y-m-d', $request->data_inicio)->startOfMonth()->format('Y-m-d');
                            $dataFim = Carbon::createFromFormat('Y-m-d', $request->data_inicio)->endOfMonth()->format('Y-m-d');
                            $query->where([
                                ['quitado', false],
                                ['data_pagamento', '<', Carbon::now()->format('Y-m-d')]
                            ])->whereBetween('data_pagamento', [$dataInicio, $dataFim]);
                        })
                        ->when($request->periodo === 'personalizado', function ($query) use ($request) {
                            $dataInicio = Carbon::createFromFormat('Y-m-d', $request->data_inicio)->format('Y-m-d');
                            $dataFim = Carbon::createFromFormat('Y-m-d', $request->data_fim)->format('Y-m-d');
                            $query->where([
                                ['quitado', false],
                                ['data_pagamento', '<', Carbon::now()->format('Y-m-d')]
                            ])->whereBetween('data_pagamento', [$dataInicio, $dataFim]);
                        });
                })
                    ->when($request->tipo_pagamento === 'a_vencer', function ($query) use ($request) {
                        $query->when($request->periodo === 'hoje', function ($query) use ($request) {
                            $query->where([
                                ['data_pagamento', $request->data_inicio],
                                ['quitado', false],
                            ]);
                        })
                            ->when($request->periodo === 'mes', function ($query) use ($request) {
                                $dataInicio = Carbon::createFromFormat('Y-m-d', $request->data_inicio)->startOfMonth()->format('Y-m-d');
                                $dataFim = Carbon::createFromFormat('Y-m-d', $request->data_inicio)->endOfMonth()->format('Y-m-d');
                                $query->where([
                                    ['quitado', false],
                                    ['data_pagamento', '>=', Carbon::now()->format('Y-m-d')]
                                ])->whereBetween('data_pagamento', [$dataInicio, $dataFim]);
                            })
                            ->when($request->periodo === 'personalizado', function ($query) use ($request) {
                                $dataInicio = Carbon::createFromFormat('Y-m-d', $request->data_inicio)->format('Y-m-d');
                                $dataFim = Carbon::createFromFormat('Y-m-d', $request->data_fim)->format('Y-m-d');
                                $query->where([
                                    ['quitado', false],
                                    ['data_pagamento', '>=', Carbon::now()->format('Y-m-d')]
                                ])->whereBetween('data_pagamento', [$dataInicio, $dataFim]);
                            });
                    })
                    ->when($request->tipo_pagamento === 'quitado', function ($query) use ($request) {
                        $query->when($request->periodo === 'hoje', function ($query) use ($request) {
                            $query->where([
                                ['data_pagamento', $request->data_inicio],
                                ['quitado', true],
                            ]);
                        })
                            ->when($request->periodo === 'mes', function ($query) use ($request) {
                                $dataInicio = Carbon::createFromFormat('Y-m-d', $request->data_inicio)->startOfMonth()->format('Y-m-d');
                                $dataFim = Carbon::createFromFormat('Y-m-d', $request->data_inicio)->endOfMonth()->format('Y-m-d');
                                $query->where('quitado', true)
                                    ->whereBetween('data_pagamento', [$dataInicio, $dataFim]);
                            })
                            ->when($request->periodo === 'personalizado', function ($query) use ($request) {
                                $dataInicio = Carbon::createFromFormat('Y-m-d', $request->data_inicio)->format('Y-m-d');
                                $dataFim = Carbon::createFromFormat('Y-m-d', $request->data_fim)->format('Y-m-d');
                                $query->where('quitado', true)->whereBetween('data_pagamento', [$dataInicio, $dataFim]);
                            });
                    })
                    ->when($request->tipo_pagamento === 'todos', function ($query) use ($request) {
                        $query->when($request->periodo === 'hoje', function ($query) use ($request) {
                            $query->where('data_pagamento', $request->data_inicio);
                        })
                            ->when($request->periodo === 'mes', function ($query) use ($request) {
                                $dataInicio = Carbon::createFromFormat('Y-m-d', $request->data_inicio)->startOfMonth()->format('Y-m-d');
                                $dataFim = Carbon::createFromFormat('Y-m-d', $request->data_inicio)->endOfMonth()->format('Y-m-d');
                                $query->whereBetween('data_pagamento', [$dataInicio, $dataFim]);
                            })
                            ->when($request->periodo === 'personalizado', function ($query) use ($request) {
                                $dataInicio = Carbon::createFromFormat('Y-m-d', $request->data_inicio)->format('Y-m-d');
                                $dataFim = Carbon::createFromFormat('Y-m-d', $request->data_fim)->format('Y-m-d');
                                $query->whereBetween('data_pagamento', [$dataInicio, $dataFim]);
                            });
                    });
            })->when($request->tipo_busca === 'cliente', function ($query) use ($request) {
                $query->whereHas('Contrato.Cliente', function ($query) use ($request) {
                    $query->where('nome_razao_social', 'like', '%'. strtoupper($request->filtro).'%');
                })->when($request->tipo_pagamento === 'em_atraso', function ($query) {
                    $query->where([
                        ['quitado', false],
                        ['data_pagamento', '<', Carbon::now()->format('Y-m-d')]
                    ]);
                })->when($request->tipo_pagamento === 'a_vencer', function ($query) {
                    $query->where([
                        ['quitado', false],
                        ['data_pagamento', '>=', Carbon::now()->format('Y-m-d')]
                    ]);
                })->when($request->tipo_pagamento === 'quitado', function ($query) {
                    $query->where('quitado', true);
                });
            })->orderBy('data_pagamento', 'ASC')->get();



            if (count($contratoPagamentos) > 0) {
                /**
                 * @var ContratoPagamento $contratoPagamento
                 */
                foreach ($contratoPagamentos as $contratoPagamento) {

                    $dataPagamento = Carbon::createFromFormat('Y-m-d', $contratoPagamento->data_pagamento);

                    $retorno[] = [
                        'id' => $contratoPagamento->id,
                        'contrato' => $contratoPagamento->Contrato->id,
                        'contratante' => $contratoPagamento->Contrato->Cliente->nome_razao_social,
                        'data_pagamento' => Carbon::createFromFormat('Y-m-d', $contratoPagamento->data_pagamento)->format('d/m/Y'),
                        'valor' =>  'R$ ' . number_format($contratoPagamento->valor, 2, ',', '.'),
                        'situacao' => $contratoPagamento->quitado ? 'quitado' :( $dataHoje <= $dataPagamento ? 'a_vencer' : 'em_atraso')
                    ];
                }
            }

            return response()->json(["success" => true, "data" => $retorno], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function quitarParcela(Request $request)
    {
        $this->validate($request, [
            'contrato_pagamento_id' => 'required|numeric|exists:contrato_pagamentos,id',
            'quitado'               => 'required|boolean'
        ]);

        try {

            /**
             * @var ContratoPagamento $contratoPagamento
             */
            $contratoPagamento = ContratoPagamento::whereId($request->contrato_pagamento_id)->first();

            $contratoPagamento->quitado = $request->quitado;
            $contratoPagamento->save();

            return response()->json(["success" => true, "message" => "Pagamento alterado com sucesso"], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }
}
