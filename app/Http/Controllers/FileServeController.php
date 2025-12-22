<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileServeController extends Controller
{
    public function show(Request $request, File $file)
    {
        // 1. Verificamos firma
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        // 2. Usamos el disco configurado
        $diskName = config('uploads.disk');

        // 3. Verificamos existencia
        if (! Storage::disk($diskName)->exists($file->path)) {
            abort(404);
        }

        // 4. Servir el archivo
        return Storage::disk($diskName)->response($file->path, $file->name, [
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => 'inline; filename="'.$file->name.'"',
        ]);
    }
}
