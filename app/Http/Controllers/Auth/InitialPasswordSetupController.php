<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class InitialPasswordSetupController extends Controller
{
    public function store(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'password' => [
                'required',
                'min:8', // Mínimo 8 caracteres
                'regex:/[a-z]/',    // Al menos una letra minúscula
                'regex:/[A-Z]/',    // Al menos una letra mayúscula
                'regex:/[0-9]/',    // Al menos un número
            ],
            'password_confirmation' => 'required|same:password',
        ], [
            'password.regex' => 'La contraseña debe contener al menos una letra minúscula, una mayúscula y un número.',
            'password_confirmation.same' => 'La confirmación de la contraseña no coincide.',
        ]);

        try {
            // Obtener el usuario autenticado
            $user = Auth::user();

            // Actualizar la contraseña directamente
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'message' => 'Contraseña actualizada correctamente.',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar la contraseña: ' . $e->getMessage());
            return response()->json([
                'error' => 'Ha ocurrido un error inesperado. Por favor, intenta nuevamente.',
            ], 500);
        }
    }
}