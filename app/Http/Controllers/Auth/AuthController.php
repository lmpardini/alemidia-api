<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'usuario' => 'required',
            'password' => 'required',
        ]);

        try {

            $credentials = $request->only('usuario', 'password');

            if (!auth()->attempt($credentials)) {
                throw new \Exception("Usuário/Senha Invalidos", 401);
            }

            $user = auth()->user();

            if (!$user->ativo){
                throw new \Exception("Seu usuário foi desativado pelo administrador do sistema.
                                      Para maiores informações entre em contato com o seu gestor");
            }

            $user->roles = $user->getRoleNames();

            $user->regra_acesso = $user->roles;

            unset($user->roles);

            $token = auth()->user()->createToken('access_token');

            return response()->json(["success" => true, "data" => ["user" => $user ,"token" => $token->plainTextToken]], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 401);
        }
    }

    public function logout(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                throw new \Exception("Usuário não está logado", 401);
            }

            $user->tokens()->delete();

            return response()->json(["success" => true, "message" => "Usuário deslogado com sucesso"], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 401);
        }
    }

    public function isLogged(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                throw new \Exception("Usuário não está logado", 401);
            }


            return response()->json(["success" => true, "data" => ["user" => $user ]], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 401);
        }
    }
}
