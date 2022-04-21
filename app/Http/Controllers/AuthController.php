<?php

namespace App\Http\Controllers;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function login() {
        return response()->json([
            'status'    => 'success',
            'message'   => 'Login',
            'data'      => []
        ], 200);
    }

    public function checkUser() {
        return response()->json([
            'status'    => 'success',
            'message'   => 'Login',
            'data'      => []
        ], 200);
    }

    public function logout() {
        return response()->json([
            'status'    => 'success',
            'message'   => 'Logout',
            'data'      => []
        ], 200);
    }
}
