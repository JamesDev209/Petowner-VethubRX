<?php

// app/Http/Controllers/PetsDashboardController.php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PetsDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pets = $user->pets; // ← ahora sí traemos las mascotas

        return Inertia::render('Pets/PetsDashboard', [
            'user' => $user,
            'pets' => $pets, // ← las pasamos al componente
        ]);
    }
}
