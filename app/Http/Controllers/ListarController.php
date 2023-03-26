<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use App\Models\Produto;
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
}
