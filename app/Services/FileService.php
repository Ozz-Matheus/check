<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function createFiles(Model $model, array $data): void
    {
        foreach ($data['path'] ?? [] as $path) {

            $fileName = $data['name'][$path] ?? basename($path);

            $fileMetadata = [
                'name' => $fileName,
                'path' => $path,
                'mime_type' => Storage::disk('public')->mimeType($path),
                'size' => Storage::disk('public')->size($path),
                'context' => $data['context'] ?? null,
            ];

            $model->files()->create($fileMetadata);
        }
    }
}
