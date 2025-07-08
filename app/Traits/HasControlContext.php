<?php

namespace App\Traits;

use App\Models\Control;

trait HasControlContext
{
    public ?int $control_id = null;

    public ?Control $controlModel = null;

    public function loadControlContext(): void
    {
        $this->control_id = request()->route('control');

        $this->controlModel = Control::findOrFail($this->control_id);
    }
}
