<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TrocarSenhaUserController extends Controller
{
    public function trocarSenha(Request $request)
    {
        $this->validate($request, [
            'password_confirmation' => 'required|min:8'
        ]);

        try {

            /**
             * @var User $user
             */
            $user = $request->user();

            if (!$user){
                throw new \Exception("Não foi encontrado usuário para este token");
            }

            $user->password = bcrypt($request->password);
            $user->primeiro_acesso = false;
            $user->save();

            return response()->json(["success" => true, "message" => "Senha alterada com sucesso"], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }

    }
}
