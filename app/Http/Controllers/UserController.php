<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function store(Request $request) {
        $user = new User();

        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->name = $request->nombres . ' ' . $request->apellidos;
        $user->email = ($request->email) ? $request->email : 'nomail@itsjc.com';
        $user->id_instituto = $request->id_instituto;
        $user->cargo = $request->cargo;
        $user->rol = $request->rol;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado correctamente.'
        ]);
    }


    public function login(UserRequest $request)
    {

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas.'
            ], 401);
        }

        // Crear token personal
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        // Revocar el token actual del usuario
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente.'
        ]);
    }


}
