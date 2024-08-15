<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'identificador' => 'required|string|max:14|min:14|unique:users',
            'senha' => 'required|string|min:10',
            'tipo' => 'required|in:P,F',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'nome' => $request->nome,
            'identificador' => $request->identificador,
            'senha' => bcrypt($request->senha),
            'tipo' => $request->tipo,
        ]);

        return response()->json([
            'message' => 'Usuário criado com sucesso',
            'user' => $user,
        ], 201);
        
    }
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
        return response()->json($user);
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'identificador' => 'required|string|max:14|min:14|unique:users',
            'senha' => 'required|string|min:10',
            'tipo' => 'required|in:P,F',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update($request->only(['nome', 'identificador', 'senha', 'tipo']));

        if ($request->has('senha')) {
            $user->senha = bcrypt($request->senha);
            $user->save();
        }

        return response()->json(['message' => 'Usuário atualizado com sucesso', 'user' => $user]);
    }
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'Usuário deletado com sucesso']);
    }

    public function getUsuariosTipoP()
    {
        $usuariosTipoP = User::where('tipo', 'P')->get();
        return response()->json($usuariosTipoP);
    }
}
    
