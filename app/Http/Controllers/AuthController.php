<?php

namespace App\Http\Controllers;

class AuthController extends Controller
{
    public function login()
    {
        return ['login'];
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
