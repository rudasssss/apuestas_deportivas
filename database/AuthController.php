<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(Request $request){

        $validador = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validador->fails()){
            return response()->json(['errors' => $validador->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        if(! JWTAuth::attempt($credentials)){
            return response()->json([
                'message' => 'Credenciales invalidas'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $code = rand(100000,999999);

        $user->verification_code = $code;
        $user->save();

        Mail::raw("Tu código de verificación es: $code", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Codigo de verificacion');
        });


        return response()->json([
            'message' => 'Código de verificación enviado al correo'
        ]);
    }

    public function me(){
        $user = auth()->user();

        return response()->json([
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "role" => $user->role
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        if ($user->verification_code != $request->code) {
            return response()->json([
                'message' => 'Código incorrecto'
            ], 400);
        }

        $user->is_verified = true;
        $user->verification_code = null;
        $user->save();

        $token = auth('api')->login($user);

        return response()->json([
            'message' => 'Verificación exitosa',
            'token' => $token
        ]);
    }

    public function logout(){
        try {

            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'message' => 'Logout realizado correctamente'
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'error' => 'No se pudo cerrar sesión'
            ], 500);

        }
    }

    public function refresh(){
        try {

            $newToken = JWTAuth::refresh(JWTAuth::getToken());

            return response()->json([
                'token' => $newToken
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'error' => 'No se pudo refrescar el token'
            ], 500);

        }
    }

    public function miCuenta(){
        $user = auth()->user()->fresh();

        return response()->json([
            "usuario" => $user->name,
            "email" => $user->email,
            "rol" => $user->rol,
            "saldo" => $user->saldo
        ]);
    }

    public function cobrarSaldo(){
        $user = auth()->user();

        $saldo = $user->saldo;

        $user->saldo = 0;
        $user->save();

        return response()->json([
            "mensaje" => "Saldo cobrado correctamente",
            "valor_cobrado" => $saldo
        ]);
    }
    
}
