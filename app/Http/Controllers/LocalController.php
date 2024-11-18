<?php

// app/Http/Controllers/LocalController.php
namespace App\Http\Controllers;

use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LocalController extends Controller
{
    // Listar locales
    public function index()
    {
        $locales = Local::all();
        return response()->json($locales);
    }

    // Crear un local con imagen
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:libre,ocupado',
            'direccion' => 'nullable|string|max:255',
            'tamano' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('imagenes', 'public');
            $validatedData['imagen'] = $path;
        }

        $local = Local::create($validatedData);

        return response()->json($local, 201);
    }

    // Mostrar un local específico
    public function show($id)
    {
        $local = Local::findOrFail($id);
        return response()->json($local);
    }

    // Actualizar un local
    public function update(Request $request, $id)
    {
        $local = Local::findOrFail($id);

        $validatedData = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:libre,ocupado',
            'direccion' => 'nullable|string|max:255',
            'tamano' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            // Eliminar la imagen antigua si existe
            if ($local->imagen && Storage::disk('public')->exists($local->imagen)) {
                Storage::disk('public')->delete($local->imagen);
            }

            // Subir nueva imagen
            $path = $request->file('imagen')->store('imagenes', 'public');
            $validatedData['imagen'] = $path;
        }

        $local->update($validatedData);

        return response()->json($local);
    }

    // Eliminar un local
    public function destroy($id)
    {
        $local = Local::findOrFail($id);

        if ($local->imagen && Storage::disk('public')->exists($local->imagen)) {
            Storage::disk('public')->delete($local->imagen);
        }

        $local->delete();

        return response()->json(['message' => 'Local eliminado correctamente.']);
    }
}

