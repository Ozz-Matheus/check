<?php

namespace App\Models;

use App\Traits\FixedMorphType;

class ControlFile extends File
{
    use FixedMorphType;

    protected $table = 'files';

    protected static string $fixedMorphType = Control::class;
}
