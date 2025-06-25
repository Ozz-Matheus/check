<?php

namespace App\Models;

use App\Traits\FixedMorphType;

class ActionTaskComment extends Comment
{
    use FixedMorphType;

    protected $table = 'comments';

    protected static string $fixedMorphType = ActionTask::class;
}
