<?php

namespace App\Http\Controllers;

use App\Models\Buffet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BuffetController extends Controller
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

            $buffets = Buffet::select('tipo_cadastro','id','nome_razao_social', 'mail', 'celular', 'cidade')
                ->when($request->filtro, function ($query, $filter) {
                    $query->where('nome_razao_social', 'like', '%'. strtoupper($filter).'%');
                })->when($request->label && $request->dir, function ($query) use ($request) {
                    $query->orderBy($request->label, $request->dir);
                })->paginate($request->per_page);

            return response()->json(["success" => true, "data" => $buffets], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
   }

    public function show(Request $request, int $id)
    {
        try {

            $buffet = Buffet::whereId($id)->first();

            if (!$buffet) {
                throw new \Exception("Id inexistente");
            }

            return response()->json(["success" => true, "data" => $buffet], 200);
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

            $buffet = Buffet::where('slug', Str::slug($request->nome_razao_social))->first();

            if ($buffet) {
                throw new \Exception("Já existe um buffet cadastrado com esse nome/razão social");
            }

            $buffet = new Buffet();
            $buffet->tipo_cadastro = $request->tipo_cadastro;
            $buffet->nome_razao_social = $request->nome_razao_social;
            $buffet->slug = Str::slug($request->nome_razao_social, '_');
            $buffet->mail = $request->mail;
            $buffet->telefone = $request->telefone;
            $buffet->celular = $request->celular;
            $buffet->celular2 = $request->celular2;
            $buffet->cep = $request->cep;
            $buffet->logradouro = $request->logradouro;
            $buffet->numero = $request->numero;
            $buffet->complemento = $request->complemento;
            $buffet->bairro = $request->bairro;
            $buffet->cidade = $request->cidade;
            $buffet->estado = $request->estado;
            $buffet->banco = $request->banco;
            $buffet->agencia = $request->agencia;
            $buffet->tipo_cadastro = $request->tipo_cadastro;
            $buffet->cc = $request->cc;
            $buffet->cpf_cnpj = $request->cpf_cnpj;
            $buffet->chave_pix = $request->chave_pix;
            $buffet->comissao = $request->comissao;
            $buffet->ativo = true;
            $buffet->save();

            return response()->json(["success" => true, "message" => "Buffet cadastrado com Sucesso"], 200);
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
             * @var Buffet $buffet
             */
            $buffet = Buffet::whereId($id)->first();

            if (!$buffet) {
                throw new \Exception("Id inexistente");
            }

            if ($buffet && $buffet->id !== $id) {
                throw new \Exception("Já existe um buffet cadastrado com este nome", 422);
            }

            $buffet->tipo_cadastro = $request->tipo_cadastro;
            $buffet->nome_razao_social = $request->nome_razao_social;
            $buffet->slug = Str::slug($request->nome_razao_social, '_');
            $buffet->mail = $request->mail;
            $buffet->telefone = $request->telefone;
            $buffet->celular = $request->celular;
            $buffet->celular2 = $request->celular2;
            $buffet->cep = $request->cep;
            $buffet->logradouro = $request->logradouro;
            $buffet->numero = $request->numero;
            $buffet->complemento = $request->complemento;
            $buffet->bairro = $request->bairro;
            $buffet->cidade = $request->cidade;
            $buffet->estado = $request->estado;
            $buffet->banco = $request->banco;
            $buffet->agencia = $request->agencia;
            $buffet->tipo_cadastro = $request->tipo_cadastro;
            $buffet->cc = $request->cc;
            $buffet->cpf_cnpj = $request->cpf_cnpj;
            $buffet->chave_pix = $request->chave_pix;
            $buffet->comissao = $request->comissao;
            $buffet->ativo = $request->ativo;
            $buffet->save();

            return response()->json(["success" => true, "message" => "Buffet alterado com sucesso"], 200);
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

           $buffets = Buffet::select('tipo_cadastro','id','nome_razao_social', 'mail', 'celular', 'cidade')
               ->when($request->filtro, function ($query, $filter) {
                   $query->where('nome_razao_social', 'like', '%'. strtoupper($filter).'%');
               })
               ->where('ativo', true)
               ->get();

           return response()->json(["success" => true, "data" => $buffets], 200);
       } catch (\Exception $e) {
           return response()->json(["success" => false, "message" => $e->getMessage()], 400);
       }
   }
}
