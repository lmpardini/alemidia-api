<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        try {

            $roles = Role::all();

            return response()->json(["success" => true, "data" => $roles], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nome_regra' => 'required|string|min:3',
            'regra'      => 'required|string|min:3'
        ]);

        try {

            $roles = Role::create(['name' => $request->regra]);

            return response()->json(["success" => true, "message" => "Regra de acesso criada com sucesso"], 200);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], 400);
        }
    }



}
