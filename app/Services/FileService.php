<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function createFiles(Model $model, array $data): void
    {
        $disk = config('uploads.disk');

        foreach ($data['path'] ?? [] as $path) {

            $fileName = $data['name'][$path] ?? basename($path);

            $fileMetadata = [
                'name' => $fileName,
                'path' => $path,
                'mime_type' => Storage::disk($disk)->mimeType($path),
                'size' => Storage::disk($disk)->size($path),
                'context' => $data['context'] ?? null,
            ];

            $model->files()->create($fileMetadata);
        }
    }
}
