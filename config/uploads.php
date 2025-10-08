<?php

return [
    'disk' => env('UPLOADS_DISK', 'public'),
    'mimes' => [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ],
    'max_mb' => 10,
];
