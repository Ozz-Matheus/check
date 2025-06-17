<?php

namespace App\Models;

use App\Traits\FixedMorphType;

class ActionTaskFile extends File
{
    use FixedMorphType;

    protected $table = 'files';

    protected static string $fixedMorphType = ActionTask::class;
}
