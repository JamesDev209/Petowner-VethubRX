<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Services\User\UserService;
use App\Services\User\VerificationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    
    public function __construct(
        protected UserService $userService,
        protected VerificationService $verificationService
    ) {}

    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        // Crear el usuario
        $user = $this->userService->createUserWithCode($request->validated());
    
        // Enviar el código de verificación
        $this->verificationService->sendVerificationCode($user);
    
        // Disparar el evento de registro
        event(new Registered($user));
    
        // Autenticar al usuario
        Auth::login($user);
    
        // Devolver una respuesta JSON
        return response()->json([
            'message' => 'User registered successfully',
            'redirect' => route('verify.account'), // Ruta a la que redirigir
        ], 201);
    }
}
