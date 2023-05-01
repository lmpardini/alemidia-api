<?php

namespace App\Http\Controllers;

use App\Models\Assessoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AssessoriaController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'filtro'=> '',
            'per_page' => 'required',
            'label' => '',
            'dir' => '',
        ]);

        try {

            $assessorias = Assessoria::select('tipo_cadastro','id','nome_razao_social', 'mail', 'celular', 'cidade')
                ->when($request->filtro, function ($query, $filter) {
                    $query->where('nome_razao_social', 'like', '%'. strtoupper($filter).'%');
                })->when($request->label && $request->dir, function ($query) use ($request) {
                    $query->orderBy($request->label, $request->dir);
                })->paginate($request->per_page);

            return response()->json(["success" => true, "data" => $assessorias], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function show(Request $request, int $id)
    {
        try {

            $assessoria = Assessoria::whereId($id)->first();

            if (!$assessoria) {
                throw new \Exception("Id inexistente");
            }

            return response()->json(["success" => true, "data" => $assessoria], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'tipo_cadastro'     => 'required|string',
            'nome_razao_social' => 'required|string',
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
            'comissao'         => 'nullable|string',
        ]);

        try {

            $assessoria = Assessoria::where('slug', Str::slug($request->nome_razao_social, '_'))->first();

            if ($assessoria) {
                throw new \Exception("Já existe uma Assessoria cadastrada com esse nome/razão social");
            }

            $assessoria = new Assessoria();
            $assessoria->tipo_cadastro = $request->tipo_cadastro;
            $assessoria->nome_razao_social = $request->nome_razao_social;
            $assessoria->slug = Str::slug($request->nome_razao_social, '_');
            $assessoria->mail = $request->mail;
            $assessoria->telefone = $request->telefone;
            $assessoria->celular = $request->celular;
            $assessoria->celular2 = $request->celular2;
            $assessoria->cep = $request->cep;
            $assessoria->logradouro = $request->logradouro;
            $assessoria->numero = $request->numero;
            $assessoria->complemento = $request->complemento;
            $assessoria->bairro = $request->bairro;
            $assessoria->cidade = $request->cidade;
            $assessoria->estado = $request->estado;
            $assessoria->banco = $request->banco;
            $assessoria->agencia = $request->agencia;
            $assessoria->tipo_cadastro = $request->tipo_cadastro;
            $assessoria->cc = $request->cc;
            $assessoria->cpf_cnpj = $request->cpf_cnpj;
            $assessoria->chave_pix = $request->chave_pix;
            $assessoria->comissao = $request->comissao;
            $assessoria->ativo = true;
            $assessoria->save();

            return response()->json(["success" => true, "message" => "Assessoria cadastrada com Sucesso"], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, int $id)
    {
        $this->validate($request, [
            'tipo_cadastro'     => 'required|string',
            'nome_razao_social' => 'required|string',
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
            'comissao'         => 'nullable|string',
            'ativo'             => 'required|boolean',
        ]);

        try {

            /**
             * @var Assessoria $assessoria
             */
            $assessoria = Assessoria::whereId($id)->first();

            if (!$assessoria) {
                throw new \Exception("Id inexistente");
            }

            if ($assessoria && $assessoria->id !== $id) {
                throw new \Exception("Já existe uma Assessoria cadastrada com este nome", 422);
            }

            $assessoria->tipo_cadastro = $request->tipo_cadastro;
            $assessoria->nome_razao_social = $request->nome_razao_social;
            $assessoria->slug = Str::slug($request->nome_razao_social, '_');
            $assessoria->mail = $request->mail;
            $assessoria->telefone = $request->telefone;
            $assessoria->celular = $request->celular;
            $assessoria->celular2 = $request->celular2;
            $assessoria->cep = $request->cep;
            $assessoria->logradouro = $request->logradouro;
            $assessoria->numero = $request->numero;
            $assessoria->complemento = $request->complemento;
            $assessoria->bairro = $request->bairro;
            $assessoria->cidade = $request->cidade;
            $assessoria->estado = $request->estado;
            $assessoria->banco = $request->banco;
            $assessoria->agencia = $request->agencia;
            $assessoria->tipo_cadastro = $request->tipo_cadastro;
            $assessoria->cc = $request->cc;
            $assessoria->cpf_cnpj = $request->cpf_cnpj;
            $assessoria->chave_pix = $request->chave_pix;
            $assessoria->comissao = $request->comissao;
            $assessoria->ativo = $request->ativo;
            $assessoria->save();

            return response()->json(["success" => true, "message" => "Assessoria alterada com sucesso"], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function listarAtivos(Request $request)
    {
        $this->validate($request, [
            'filtro'=> ''
        ]);

        try {

            $assessorias = Assessoria::select('tipo_cadastro','id','nome_razao_social', 'mail', 'celular', 'cidade')
                ->when($request->filtro, function ($query, $filter) {
                    $query->where('nome_razao_social', 'like', '%'. strtoupper($filter).'%');
                })
                ->where('ativo', true)
                ->get();

            return response()->json(["success" => true, "data" => $assessorias], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }
}
