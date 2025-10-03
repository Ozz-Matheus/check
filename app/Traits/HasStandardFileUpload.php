<?php

namespace App\Traits;

use Filament\Forms\Components\FileUpload;

trait HasStandardFileUpload
{
    public static function baseFileUpload(string $name = 'file'): FileUpload
    {
        return FileUpload::make($name)
            ->storeFileNamesIn('name')
            ->disk('public')
            ->acceptedFileTypes([
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])
            ->maxSize(10240)
            ->helperText(__('Tipos permitidos: PDF, DOC/DOCX, XLS/XLSX (máx. 10MB)'));
    }
}
