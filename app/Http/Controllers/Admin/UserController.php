<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request,[
            'filtro'=> ''
        ]);

        try {

            $usuarios = User::select('id','nome', 'email', 'usuario', 'ativo')
                ->when($request->filtro, function ($query, $filter) {
                    $query->where('nome', 'like', '%'. strtoupper($filter).'%');
                })->get();

            return response()->json(["success" => true, "data" => $usuarios], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function show(int $id)
    {
        try {

            $user = User::whereId($id)->with('roles')->first();

            if (!$user){
                throw new \Exception("Usuário não encontrado");
            }

            $role = $user->roles->map(function ($item) {
                return $item->name;
            })->flatten()->values();

            $user->role = $role;
            unset($user->roles);

            return response()->json(["success" => true, "data" => $user], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nome'      => 'required|string|unique:users,nome',
            'usuario'      => 'required|string|min:3|unique:users,usuario',
            'email'     => 'required|string|email',
            'role' => 'required|exists:roles,name'
        ]);

        try {

            $password = $this->generatePassword();

            $user = new User();
            $user->nome = $request->nome;
            $user->usuario = $request->usuario;
            $user->email = $request->email;
            $user->password = bcrypt($password);
            $user->primeiro_acesso = true;
            $user->assignRole($request->role);
            $user->save();

            MailService::sendMailNovoCadastro($password, $user);


            return response()->json(["success" => true, "message" => "Usuário criado com sucesso"], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, int $id)
    {
        $this->validate($request, [
            'nome'      => ['required','string',Rule::unique('users', 'nome')->ignore($id)],
            'usuario'   => ['required','string','min:3',Rule::unique('users', 'usuario')->ignore($id)],
            'email'     => 'required|string|email',
            'role'      => 'required|exists:roles,name',
            'ativo'     => 'required|boolean'
        ]);

        try {

            /**
             * @var User $user
             */
            $user = User::whereId($id)->with('roles')->first();

            if (!$user){
                throw new \Exception("Usuário não encontrado");
            }

            $role = $user->roles->map(function ($item) {
                return $item->name;
            })->flatten()->values()->toArray();

            $user->nome = $request->nome;
            $user->usuario = $request->usuario;
            $user->email = $request->email;
            $user->ativo = $request->ativo;
            $user->removeRole($role[0]);
            $user->assignRole($request->role);
            $user->save();

            return response()->json(["success" => true, "message" => "Usuário editado com sucesso"], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function redefinirSenha(Request $request, int $id)
    {
        try {

            /**
             * @var User $user
             */
            $user = User::whereId($id)->first();

            if (!$user){
                throw new \Exception("Usuário não encontrado");
            }

            $password = $this->generatePassword();
            $user->password = bcrypt($password);
            $user->primeiro_acesso = true;
            $user->save();

            MailService::sendMailRedefinicaoSenha($password, $user);

            return response()->json(["success" => true, "message" => "Senha redefinida com sucesso"], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }

    }

    private function generatePassword($qtyCaraceters = 8)
    {
        //Letras minúsculas embaralhadas
        $smallLetters = str_shuffle('abcdefghijklmnopqrstuvwxyz');

        //Letras maiúsculas embaralhadas
        $capitalLetters = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');

        //Números aleatórios
        $numbers = (((date('Ymd') / 12) * 24) + mt_rand(800, 9999));
        $numbers .= 1234567890;

        //Caracteres Especiais
        $specialCharacters = str_shuffle('!@#$%*-');

        //Junta tudo
        $characters = $capitalLetters . $smallLetters . $numbers . $specialCharacters;

        //Embaralha e pega apenas a quantidade de caracteres informada no parâmetro
        $password = substr(str_shuffle($characters), 0, $qtyCaraceters);

        //Retorna a senha
        return $password;
    }
}
