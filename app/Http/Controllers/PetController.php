<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PetController extends Controller
{
    // (ya lo tenías)
    public function create()
    {
        return Inertia::render('Pets/AddPet');
    }

    // (ya lo tenías)
    public function store(Request $request)
    {
        // validación…
        Auth::user()->pets()->create($request->all());
        return redirect()->route('pets.dashboard')
                         ->with('success', 'Mascota registrada exitosamente.');
    }

    // ← Este es el nuevo método
    public function index(Request $request)
    {
        // Devuelve un JSON con todas las mascotas del usuario
        return response()->json(
            Auth::user()->pets()->get()
        );
    }

    // (opcional) si no lo tienes, define el método que renderiza el dashboard
    public function dashboard()
    {
        // Renderiza la vista de Vue; no le pasamos aún datos,
        // porque los traeremos vía AJAX/fetch
        return Inertia::render('Pets/Dashboard');
    }
}
