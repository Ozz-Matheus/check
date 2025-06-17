<?php

namespace App\Models;

use App\Traits\FixedMorphType;

class ActionEndingFile extends File
{
    use FixedMorphType;

    protected $table = 'files';

    protected static string $fixedMorphType = ActionEnding::class;
}
