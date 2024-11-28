<?php

namespace App\Http\Controllers;

use App\Models\Novedad;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class NovedadController extends Controller
{

    public function index()
    {
        return response()->json(Novedad::orderBy('fecha', 'desc')->get());
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha' => 'required|date',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
        ]);
    
        $imagenUrl = null;
    
        if ($request->hasFile('imagen')) {
            
            $uploadedFileUrl = Cloudinary::upload(
                $request->file('imagen')->getRealPath(),
                ['folder' => 'novedades']
            )->getSecurePath();
    
            $imagenUrl = $uploadedFileUrl;
        }
    
        $novedad = Novedad::create(array_merge($validated, ['imagen_url' => $imagenUrl]));
    
        return response()->json($novedad, 201);
    }


    public function update(Request $request, $id)
    {
        $novedad = Novedad::findOrFail($id);
    
        $validated = $request->validate([
            'titulo' => 'string|max:255',
            'descripcion' => 'string',
            'fecha' => 'date',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
        ]);
    
     
        if ($request->hasFile('imagen')) {
           
            if ($novedad->imagen_url) {
              
                $publicId = basename($novedad->imagen_url, '.' . pathinfo($novedad->imagen_url, PATHINFO_EXTENSION));
                Cloudinary::destroy('novedades/' . $publicId);
            }
    
          
            $uploadedFileUrl = Cloudinary::upload(
                $request->file('imagen')->getRealPath(),
                ['folder' => 'novedades']
            )->getSecurePath();
    
            $validated['imagen_url'] = $uploadedFileUrl;
        }
    
     
        $novedad->update($validated);
    
        return response()->json($novedad);
    }

    public function destroy($id)
    {
        $novedad = Novedad::findOrFail($id);
        if ($novedad->imagen_url) {
            
            $publicId = basename($novedad->imagen_url, '.' . pathinfo($novedad->imagen_url, PATHINFO_EXTENSION));
            Cloudinary::destroy('novedades/' . $publicId);
        }
    
        $novedad->delete();
    
        return response()->json(['message' => 'Novedad eliminada con éxito']);
    }
}
