<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BuscaCepController extends Controller
{
    public function buscaCep(Request $request)
    {
        $this->validate($request, [
            //'cep' => 'required|min:8|max:8'
        ]);

        try {

            $buscaCep =  Http::withOptions(['verify' => false])->get('https://viacep.com.br/ws/'.$request->cep.'/json');

            $cep = $buscaCep->json();

            if (!$cep || isset($cep['erro'])) {
                throw new \Exception("Não foi possivel consultar o CEP informado ou CEP inexistente");
            }

            return response()->json(["success" => true, "message" => "Consulta de CEP realizada com sucesso", "data" => $cep], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }
}
