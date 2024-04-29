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
        return ['logout'];
    }

    public function refresh()
    {
        return ['refresh'];
    }

    public function me()
    {
        return ['me'];
    }
}
