<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credencias = $request->all(['email', 'password']);
        $token = auth('api')->attempt($credencias);

        if(!$token){
            return response()->json(['error' => 'Email e/ou senha inválido!'], 403);
        }
        return response()->json(['token' => $token], 200);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['success' => 'Logout relizado com sucesso.']);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();
        return response()->json(['token' => $token]); 
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
