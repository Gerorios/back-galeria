<?php

namespace App\Http\Controllers;

use App\Models\Novedad;
use Illuminate\Http\Request;

class NovedadController extends Controller
{
    // Obtener todas las novedades
    public function index()
    {
        return response()->json(Novedad::orderBy('fecha', 'desc')->get());
    }

    // Crear una nueva novedad
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha' => 'required|date',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de imagen
        ]);
    
        $imagenUrl = null;
    
        if ($request->hasFile('imagen')) {
            // Subir a Cloudinary y obtener la URL
            $uploadedFileUrl = Cloudinary::upload(
                $request->file('imagen')->getRealPath(),
                ['folder' => 'novedades']
            )->getSecurePath();
    
            $imagenUrl = $uploadedFileUrl;
        }
    
        $novedad = Novedad::create(array_merge($validated, ['imagen_url' => $imagenUrl]));
    
        return response()->json($novedad, 201);
    }

    // Actualizar una novedad existente
    public function update(Request $request, $id)
    {
        $novedad = Novedad::findOrFail($id);
    
        $validated = $request->validate([
            'titulo' => 'string|max:255',
            'descripcion' => 'string',
            'fecha' => 'date',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de imagen
        ]);
    
        // Manejo de la nueva imagen
        if ($request->hasFile('imagen')) {
            // Eliminar la imagen actual si existe
            if ($novedad->imagen_url) {
                // Extraer el ID público de Cloudinary de la URL actual
                $publicId = basename($novedad->imagen_url, '.' . pathinfo($novedad->imagen_url, PATHINFO_EXTENSION));
                Cloudinary::destroy('novedades/' . $publicId);
            }
    
            // Subir la nueva imagen a Cloudinary
            $uploadedFileUrl = Cloudinary::upload(
                $request->file('imagen')->getRealPath(),
                ['folder' => 'novedades']
            )->getSecurePath();
    
            $validated['imagen_url'] = $uploadedFileUrl;
        }
    
        // Actualizar la novedad con los datos validados
        $novedad->update($validated);
    
        return response()->json($novedad);
    }

    // Eliminar una novedad
    public function destroy($id)
    {
        $novedad = Novedad::findOrFail($id);
    
        // Eliminar la imagen de Cloudinary si existe
        if ($novedad->imagen_url) {
            // Extraer el ID público de Cloudinary de la URL actual
            $publicId = basename($novedad->imagen_url, '.' . pathinfo($novedad->imagen_url, PATHINFO_EXTENSION));
            Cloudinary::destroy('novedades/' . $publicId);
        }
    
        // Eliminar la novedad de la base de datos
        $novedad->delete();
    
        return response()->json(['message' => 'Novedad eliminada con éxito']);
    }
}
