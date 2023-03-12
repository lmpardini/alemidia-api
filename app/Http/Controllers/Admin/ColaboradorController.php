<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Colaborador;
use App\Models\ColaboradorFuncao;
use App\Models\Funcao;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ColaboradorController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'filtro'=> ''
        ]);

        try {

            $colaboradores = Colaborador::select('id','nome', 'celular', 'cidade')->with('Funcao:nome_funcao')
                ->when($request->filtro, function ($query, $filter) {
                    $query->where('nome', 'like', '%'. strtoupper($filter).'%');
                })->get();

            foreach ($colaboradores as $colaborador) {
                $funcao = $colaborador->Funcao->map(function ($item) {
                    return $item->nome_funcao;
                });

                $colaborador->funcao = $funcao;
                unset($colaborador->Funcao);
            }

            return response()->json(["success" => true, "data" => $colaboradores], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function show(int $id)
    {
        try {

            $colaborador = Colaborador::whereId($id)->with('Funcao:slug')->first();

            if (!$colaborador) {
                throw new \Exception("Id inexistente");
            }

            $funcao = $colaborador->Funcao->map(function ($item) {
                return $item->slug;
            });

            $colaborador->funcao = $funcao;
            unset($colaborador->Funcao);

            return response()->json(["success" => true, "data" => $colaborador], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nome'             => 'required|string',
            'mail'              => 'required|string',
            'telefone'          => 'nullable|string',
            'celular'           => 'required|string',
            'celular2'          => 'nullable|string',
            'cep'               => 'required|string',
            'logradouro'        => 'required|string',
            'numero'            => 'required|string',
            'complemento'       => 'nullable|string',
            'bairro'            => 'required|string',
            'cidade'            => 'required|string',
            'estado'            => 'required|string',
            'banco'             => 'nullable|string',
            'agencia'           => 'nullable|string',
            'cc'                => 'nullable|string',
            'cpf_cnpj'          => 'nullable|string',
            'chave_pix'         => 'nullable|string',
            'comissao'          => 'nullable|string',
            'funcao'            => 'required|array',
            'funcao*.*'         => 'required|exists:funcao,slug'
        ]);

        try {

            DB::beginTransaction();

            $colaborador = Colaborador::where('slug', Str::slug($request->nome, '_'))->first();

            if ($colaborador) {
                throw new \Exception("Já existe um colaborador cadastrado com esse nome");
            }

            $colaborador = new Colaborador();
            $colaborador->nome = $request->nome;
            $colaborador->slug = Str::slug($request->nome, '_');
            $colaborador->mail = $request->mail;
            $colaborador->telefone = $request->telefone;
            $colaborador->celular = $request->celular;
            $colaborador->celular2 = $request->celular2;
            $colaborador->cep = $request->cep;
            $colaborador->logradouro = $request->logradouro;
            $colaborador->numero = $request->numero;
            $colaborador->complemento = $request->complemento;
            $colaborador->bairro = $request->bairro;
            $colaborador->cidade = $request->cidade;
            $colaborador->estado = $request->estado;
            $colaborador->banco = $request->banco;
            $colaborador->agencia = $request->agencia;
            $colaborador->cc = $request->cc;
            $colaborador->cpf_cnpj = $request->cpf_cnpj;
            $colaborador->chave_pix = $request->chave_pix;
            $colaborador->comissao = $request->comissao;
            $colaborador->ativo = true;
            $colaborador->save();


            foreach ($request->funcao as $funcao) {
                /**
                 * @var Funcao $funcao
                 */
                $funcao = Funcao::where('slug', $funcao)->first();

                if (!$funcao) {
                    throw new \Exception("Não foi encontrada função para atribuir ao colaborador com os parametros informados");
                }

                $colaboradorFuncao = new ColaboradorFuncao();
                $colaboradorFuncao->colaborador_id = $colaborador->id;
                $colaboradorFuncao->funcao_id = $funcao->id;
                $colaboradorFuncao->save();
            }

            DB::commit();

            return response()->json(["success" => true, "message" => "Colaborador cadastrado com Sucesso"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, int $id)
    {
        $this->validate($request, [

            'nome'              => 'required|string',
            'mail'              => 'required|string',
            'telefone'          => 'nullable|string',
            'celular'           => 'required|string',
            'celular2'          => 'nullable|string',
            'cep'               => 'required|string',
            'logradouro'        => 'required|string',
            'numero'            => 'required|string',
            'complemento'       => 'nullable|string',
            'bairro'            => 'required|string',
            'cidade'            => 'required|string',
            'estado'            => 'required|string',
            'banco'             => 'nullable|string',
            'agencia'           => 'nullable|string',
            'cc'                => 'nullable|string',
            'cpf_cnpj'          => 'nullable|string',
            'chave_pix'         => 'nullable|string',
            'comissao'          => 'nullable|string',
            'ativo'             => 'required|boolean',
            'funcao'            => 'required|array',
            'funcao*.*'         => 'required|exists:funcao,slug'
        ]);

        try {

            DB::beginTransaction();

            /**
             * @var Colaborador $colaborador
             */
            $colaborador = Colaborador::whereId($id)->first();

            if (!$colaborador) {
                throw new \Exception("Colaborador não encontrado");
            }

            if ($colaborador && $colaborador->id !== $id) {
                throw new \Exception("Já existe um colaborador cadastrado com este nome", 422);
            }

            /**
             * Pesquisa quais são as funções, e exlui da tabela de relacionamento
             * @var ColaboradorFuncao $colaboradorFuncao
             */

            $colaboradorFuncao = ColaboradorFuncao::where('colaborador_id', $colaborador->id)->get();

            if ($colaboradorFuncao) {
                foreach ($colaboradorFuncao as $cf) {
                    $cf->delete();
                }
            }

            $colaborador->nome = $request->nome;
            $colaborador->slug = Str::slug($request->nome, '_');
            $colaborador->mail = $request->mail;
            $colaborador->telefone = $request->telefone;
            $colaborador->celular = $request->celular;
            $colaborador->celular2 = $request->celular2;
            $colaborador->cep = $request->cep;
            $colaborador->logradouro = $request->logradouro;
            $colaborador->numero = $request->numero;
            $colaborador->complemento = $request->complemento;
            $colaborador->bairro = $request->bairro;
            $colaborador->cidade = $request->cidade;
            $colaborador->estado = $request->estado;
            $colaborador->banco = $request->banco;
            $colaborador->agencia = $request->agencia;
            $colaborador->cc = $request->cc;
            $colaborador->cpf_cnpj = $request->cpf_cnpj;
            $colaborador->chave_pix = $request->chave_pix;
            $colaborador->comissao = $request->comissao;
            $colaborador->ativo = $request->ativo;
            $colaborador->save();

            /**
             * Cria novos relacionamentos com funções
             */

            foreach ($request->funcao as $funcao) {
                /**
                 * @var Funcao $funcao
                 */
                $funcao = Funcao::where('slug', $funcao)->first();

                if (!$funcao) {
                    throw new \Exception("Não foi encontrada função para atribuir ao colaborador com os parametros informados");
                }

                $colaboradorFn = new ColaboradorFuncao();
                $colaboradorFn->colaborador_id = $colaborador->id;
                $colaboradorFn->funcao_id = $funcao->id;
                $colaboradorFn->save();
            }

            DB::commit();

            return response()->json(["success" => true, "message" => "Colaborador alterado com sucesso"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }
}
