<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request,[
            'filtro'=> ''
        ]);

        try {

            $produtos = Produto::select('id','nome', 'quantidade_dia', 'ativo')
                ->when($request->filtro, function ($query, $filter) {
                    $query->where('nome', 'like', '%'. strtoupper($filter).'%');
                })->get();

            return response()->json(["success" => true, "data" => $produtos], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function show(int $id)
    {
        try {

            $produto = Produto::whereId($id)->first();

            if (!$produto){
                throw new \Exception("Produto não encontrado");
            }

            return response()->json(["success" => true, "data" => $produto], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nome'           => 'required|string',
            'descricao'      => 'required|string|min:3',
            'quantidade_dia' => 'required|numeric',

        ]);

        try {

            /**
             * @var Produto $produto
             */
            $produto = Produto::where('slug', Str::slug($request->nome, '_'))->first();

            if ($produto) {
                throw new \Exception("Já existe um produto cadastrado com esse nome");
            }

            $produto = new Produto();
            $produto->nome = $request->nome;
            $produto->slug = Str::slug($request->nome, '_');
            $produto->descricao = $request->descricao;
            $produto->quantidade_dia = $request->quantidade_dia;
            $produto->save();

            return response()->json(["success" => true, "message" => "Produto criado com sucesso"], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, int $id)
    {
        $this->validate($request, [
            'nome'           => 'required|string',
            'descricao'      => 'required|string|min:3',
            'quantidade_dia' => 'required|numeric',
            'ativo'          => 'required|boolean'
        ]);

        try {

            /**
             * @var Produto $produto
             */
            $produto = Produto::whereId($id)->first();

            if (!$produto){
                throw new \Exception("Produto não encontrado");
            }

            $produto->nome = $request->nome;
            $produto->slug = Str::slug($request->nome, '_');
            $produto->descricao = $request->descricao;
            $produto->quantidade_dia = $request->quantidade_dia;
            $produto->ativo = $request->ativo;
            $produto->save();

            return response()->json(["success" => true, "message" => "Produto editado com sucesso"], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }
}
