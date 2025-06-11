<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    // Verifica el código que el usuario ingresó
    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|digits:5',
        ]);

        $user = Auth::user();

        if ($user->verification_code == $request->verification_code) {
            $user->is_verified = true;
            $user->verification_code = null;
            $user->email_verified_at = now();
            $user->save();

            return response()->json([
                'message' => 'Código verificado correctamente',
                'redirect' => route('password.create') // Aquí puedes usar este valor para redirigir en Vue
            ]);
        }

        return response()->json([
            'errors' => [
                'verification_code' => 'El código ingresado es incorrecto.'
            ]
        ], 422);
    }

    public function resend(Request $request)
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json([
                'errors' => ['message' => 'Usuario no autenticado.']
            ], 401);
        }
    
        // Generar nuevo código de 5 dígitos
        $user->verification_code = rand(10000, 99999);
        $user->save();
    
        // Aquí solo actualizas la base de datos, sin enviar el correo
        return response()->json([
            'message' => 'Te hemos generado un nuevo código.'
        ]);
    }
}
