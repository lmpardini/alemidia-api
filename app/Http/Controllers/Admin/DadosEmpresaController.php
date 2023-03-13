<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DadosEmpresa;
use Illuminate\Http\Request;

class DadosEmpresaController extends Controller
{
   public function show()
   {
       try {

           $dadosEmpresa = DadosEmpresa::get();

           return response()->json(["success" => true, "data" => $dadosEmpresa], 200);
       } catch (\Exception $e) {
           return response()->json(["success" => false, "message" => $e->getMessage()], 400);
       }
   }

    public function update(Request $request)
    {

        $this->validate($request, [
            'id'                   => 'required|numeric|exists:dados_empresa,id',
            'nome_fantasia'        => 'required|string',
            'razao_social'         => 'required|string',
            'cnpj'                 => 'required|string',
            'inscricao_estadual'   => 'nullable|string',
            'tel_comercial'        => 'nullable|string',
            'cel_comercial'        => 'required|string',
            'cel_comercial2'       => 'nullable|string',
            'site'                 => 'required|string',
            'representante_legal'  => 'required|string',
            'cpf_representante'    => 'required|string',
            'rg_representante'     => 'required|string',
            'cep'                  => 'required|string',
            'logradouro'           => 'required|string',
            'numero'               => 'required|string',
            'complemento'          => 'nullable|string',
            'bairro'               => 'required|string',
            'cidade'               => 'required|string',
            'estado'               => 'required|string',
            ]);

        try {

            /**
             * @var DadosEmpresa $dadosEmpresa
             */

           $dadosEmpresa = DadosEmpresa::whereId($request->id)->first();

           if (!$dadosEmpresa){
               throw new \Exception("Empresa não encontrada");
           }

            $dadosEmpresa->nome_fantasia = $request->nome_fantasia;
            $dadosEmpresa->razao_social = $request->razao_social;
            $dadosEmpresa->cnpj = $request->cnpj;
            $dadosEmpresa->inscricao_estadual = $request->inscricao_estadual;
            $dadosEmpresa->tel_comercial = $request->tel_comercial;
            $dadosEmpresa->cel_comercial = $request->cel_comercial;
            $dadosEmpresa->cel_comercial2 = $request->cel_comercial2;
            $dadosEmpresa->site = $request->site;
            $dadosEmpresa->representante_legal = $request->representante_legal;
            $dadosEmpresa->cpf_representante = $request->cpf_representante;
            $dadosEmpresa->rg_representante = $request->rg_representante;
            $dadosEmpresa->cep = $request->cep;
            $dadosEmpresa->logradouro = $request->logradouro;
            $dadosEmpresa->numero = $request->numero;
            $dadosEmpresa->complemento = $request->complemento;
            $dadosEmpresa->bairro = $request->bairro;
            $dadosEmpresa->cidade = $request->cidade;
            $dadosEmpresa->estado = $request->estado;
            $dadosEmpresa->save();

            return response()->json(["success" => true, "message" => "Dados da empresa atualizado com sucesso"], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }
}
