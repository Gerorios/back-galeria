<?php

namespace App\Http\Controllers;

use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class LocalController extends Controller
{

    public function index()
    {
        $locales = Local::all();
        return response()->json($locales);
    }

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
            $result = Cloudinary::upload($request->file('imagen')->getRealPath(), [
                'folder' => 'locales', 
            ]);
            $validatedData['imagen'] = $result->getSecurePath(); 
        }
    
        $local = Local::create($validatedData);
    
        return response()->json($local, 201);
    }


    public function show($id)
    {
        $local = Local::findOrFail($id);
        return response()->json($local);
    }


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

            if ($local->imagen) {
                Cloudinary::destroy($local->imagen);
            }
            $result = Cloudinary::upload($request->file('imagen')->getRealPath(), [
                'folder' => 'locales',
            ]);
            $validatedData['imagen'] = $result->getSecurePath();
        }
    
        $local->update($validatedData);
    
        return response()->json($local);
    }

    public function destroy($id)
    {
        $local = Local::findOrFail($id);
        if ($local->imagen) {
            $publicId = $this->getPublicIdFromUrl($local->imagen);
    
            if ($publicId) {
                Cloudinary::destroy($publicId);
            }
        }
    
        $local->delete();
    
        return response()->json(['message' => 'Local eliminado correctamente.']);
    }
    private function getPublicIdFromUrl($url)
{
    $parsedUrl = parse_url($url);
    if (!isset($parsedUrl['path'])) {
        return null;
    }
    $pathParts = explode('/', $parsedUrl['path']);
    $publicIdWithExtension = end($pathParts);
    $publicId = pathinfo($publicIdWithExtension, PATHINFO_FILENAME);

    return $publicId;
}
}

