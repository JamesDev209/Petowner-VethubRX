<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerificationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\InitialPasswordSetupController;
use App\Http\Controllers\PetsDashboardController;

// Ruta para mostrar la pantalla de bienvenida
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Ruta para el dashboard
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas de perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Ruta para mostrar la pantalla de verificación del código
// Ruta para mostrar la pantalla de verificación del código
Route::get('/verify-account', function () {
    if (!Auth::check()) {
        return response()->json(['error' => 'Usuario no autenticado'], 401);
    }

    return response()->json([
        'email' => Auth::user()->email,
    ]);
})->middleware(['auth'])->name('verify.account');


// Ruta POST para enviar y verificar el código ingresado
Route::post('/verify-code', [VerificationController::class, 'verifyCode'])
    ->middleware(['auth'])
    ->name('verify.code');

Route::post('/resend-verification-code', [VerificationController::class, 'resend'])->middleware(['auth']);

// En lugar de Inertia::render(), devuelve JSON
Route::get('/set-password', function () {
    return response()->json(['email' => Auth::user()->email]);
})->middleware('auth')->name('password.create');

// 2) Guardar nueva contraseña
Route::post('/set-password', [InitialPasswordSetupController::class, 'store'])
     ->name('password.store');

// Ruta para dashboard de mascotas
Route::get('/pets-dashboard', [PetsDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('pets.dashboard');

// Ruta para crear mascotas
Route::middleware(['auth', 'verified'])->post('/pets', [PetController::class, 'store'])->name('pets.store');

// Rutas de registro
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register.form');  // Mostrar formulario de registro
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');  // Procesar registro

require __DIR__.'/auth.php';


