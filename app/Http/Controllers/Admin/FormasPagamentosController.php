<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormaPagamento;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class FormasPagamentosController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request,[
            'filtro'=> ''
        ]);

        try {

            $formaPagamentos = FormaPagamento::select('id','nome', 'ativo')
                ->when($request->filtro, function ($query, $filter) {
                    $query->where('nome', 'like', '%'. strtoupper($filter).'%');
                })->get();

            return response()->json(["success" => true, "data" => $formaPagamentos], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function show(int $id)
    {
        try {

            $formaPagamento = FormaPagamento::whereId($id)->first();

            if (!$formaPagamento){
                throw new \Exception("Forma de pagamento não encontrado");
            }

            return response()->json(["success" => true, "data" => $formaPagamento], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nome' => 'required|string',
        ]);

        try {

            /**
             * @var FormaPagamento $formaPagamento
             */
            $formaPagamento = FormaPagamento::where('slug', Str::slug($request->nome, '_'))->first();

            if ($formaPagamento) {
                throw new \Exception("Já existe uma forma de pagamento cadastrado com esse nome");
            }

            $formaPagamento = new FormaPagamento();
            $formaPagamento->nome = $request->nome;
            $formaPagamento->slug = Str::slug($request->nome, '_');
            $formaPagamento->descricao = $request->descricao;
            $formaPagamento->save();

            return response()->json(["success" => true, "message" => "Forma de pagamento criado com sucesso"], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, int $id)
    {
        $this->validate($request, [
            'nome'      => 'required|string',
            'ativo'     => 'required|boolean',
            'descricao' => 'nullable|string'
        ]);

        try {

            /**
             * @var FormaPagamento $formaPagamento
             */
            $formaPagamento = FormaPagamento::whereId($id)->first();

            if (!$formaPagamento){
                throw new \Exception("Forma de pagamento não encontrado");
            }

            $formaPagamento->nome = $request->nome;
            $formaPagamento->slug = Str::slug($request->nome, '_');
            $formaPagamento->descricao = $request->descricao;
            $formaPagamento->ativo = $request->ativo;
            $formaPagamento->save();

            return response()->json(["success" => true, "message" => "Forma de pagamento editado com sucesso"], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }
}
