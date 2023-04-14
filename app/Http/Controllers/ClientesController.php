<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientesController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'filtro'=> ''
        ]);

        try {

            $clientes = Cliente::select('tipo_cadastro','id','nome_razao_social', 'cpf_cnpj', 'mail', 'celular')
                ->when($request->filtro, function ($query, $filter) {
                    $query->where('nome_razao_social', 'like', '%'. strtoupper($filter).'%');
                })->get();

            return response()->json(["success" => true, "data" => $clientes], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function show(int $id)
    {
        try {

            $cliente = Cliente::whereId($id)->first();

            if (!$cliente) {
                throw new \Exception("Id inexistente");
            }

            return response()->json(["success" => true, "data" => $cliente], 200);
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
            'cpf_cnpj'          => 'required|numeric|unique:cliente,cpf_cnpj',
            'rg_ie'             => 'nullable|string',
            'telefone'          => 'nullable|numeric',
            'celular'           => 'required|numeric',
            'celular2'          => 'nullable|numeric',
            'logradouro'        => 'required|string',
            'numero'            => 'required|string',
            'cep'               => 'required|numeric',
            'complemento'       => 'nullable|string',
            'bairro'            => 'required|string',
            'cidade'            => 'required|string',
            'estado'            => 'required|string',
        ]);

        try {

            $cliente = new Cliente();
            $cliente->tipo_cadastro = $request->tipo_cadastro;
            $cliente->nome_razao_social = $request->nome_razao_social;
            $cliente->mail = $request->mail;
            $cliente->cpf_cnpj = $request->cpf_cnpj;
            $cliente->rg_ie = $request->rg_ie;
            $cliente->telefone = $request->telefone;
            $cliente->celular = $request->celular;
            $cliente->celular2 = $request->celular2;
            $cliente->logradouro = $request->logradouro;
            $cliente->cep = $request->cep;
            $cliente->numero = $request->numero;
            $cliente->complemento = $request->complemento;
            $cliente->bairro = $request->bairro;
            $cliente->cidade = $request->cidade;
            $cliente->estado = $request->estado;
            $cliente->ativo = true;
            $cliente->save();

            return response()->json(["success" => true, "message" => "Cliente cadastrado com sucesso"], 200);
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
            'cpf_cnpj'          => ['required','numeric', Rule::unique('cliente', 'cpf_cnpj')->ignore($request->id)->whereNull('deleted_at')],
            'rg_ie'             => 'nullable|string',
            'telefone'          => 'nullable|numeric',
            'celular'           => 'required|numeric',
            'celular2'          => 'nullable|numeric',
            'logradouro'        => 'required|string',
            'numero'            => 'required|string',
            'cep'               => 'required|numeric',
            'complemento'       => 'nullable|string',
            'bairro'            => 'required|string',
            'cidade'            => 'required|string',
            'estado'            => 'required|string',
        ]);

        try {

            /**
             * @var Cliente $cliente
             */
            $cliente = Cliente::whereId($id)->first();

            if (!$cliente) {
                throw new \Exception("Id inexistente");
            }

            $cliente->tipo_cadastro = $request->tipo_cadastro;
            $cliente->nome_razao_social = $request->nome_razao_social;
            $cliente->mail = $request->mail;
            $cliente->cpf_cnpj = $request->cpf_cnpj;
            $cliente->rg_ie = $request->rg_ie;
            $cliente->telefone = $request->telefone;
            $cliente->celular = $request->celular;
            $cliente->celular2 = $request->celular2;
            $cliente->logradouro = $request->logradouro;
            $cliente->cep = $request->cep;
            $cliente->numero = $request->numero;
            $cliente->complemento = $request->complemento;
            $cliente->bairro = $request->bairro;
            $cliente->cidade = $request->cidade;
            $cliente->estado = $request->estado;
            $cliente->ativo = $request->ativo;
            $cliente->save();

            return response()->json(["success" => true, "message" => "Cliente alterado com sucesso"], 200);
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

            $clientes = Cliente::select('tipo_cadastro','id','nome_razao_social', 'cpf_cnpj', 'mail', 'celular')
                ->when($request->filtro, function ($query, $filter) {
                    $query->where('nome_razao_social', 'like', '%'. strtoupper($filter).'%');
                })
                ->where('ativo', true)
                ->get();

            return response()->json(["success" => true, "data" => $clientes], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }
}
