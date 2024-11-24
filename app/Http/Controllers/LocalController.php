<?php

// app/Http/Controllers/LocalController.php
namespace App\Http\Controllers;

use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
    
        if ($request->hasFile('imagen')) {
            // Subir imagen a Cloudinary
            $result = Cloudinary::upload($request->file('imagen')->getRealPath(), [
                'folder' => 'locales', // Carpeta en Cloudinary
            ]);
            $validatedData['imagen'] = $result->getSecurePath(); // URL segura de la imagen
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
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
    
        if ($request->hasFile('imagen')) {
            // Subir imagen a Cloudinary y eliminar la anterior (si existe)
            if ($local->imagen) {
                Cloudinary::destroy($local->imagen); // Elimina usando el ID del recurso
            }
            $result = Cloudinary::upload($request->file('imagen')->getRealPath(), [
                'folder' => 'locales',
            ]);
            $validatedData['imagen'] = $result->getSecurePath();
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

