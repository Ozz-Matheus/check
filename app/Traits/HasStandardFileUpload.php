<?php

declare(strict_types=1);

namespace App\Traits;

use Filament\Forms\Components\FileUpload;

trait HasStandardFileUpload
{
    public static function baseFileUpload(string $name = 'file'): FileUpload
    {

        $cfg = config('uploads');

        return FileUpload::make($name)
            ->storeFileNamesIn('name')
            ->disk($cfg['disk'])
            ->acceptedFileTypes($cfg['mimes'])
            ->maxSize($cfg['max_mb'] * 1024)
            ->helperText(__('Allowed types: PDF, DOC, DOCX, XLS, XLSX (max. :mbMB)', ['mb' => $cfg['max_mb']]));

    }
}
