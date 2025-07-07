<?php

namespace App\Traits;

use App\Models\Control;

trait HasControlContext
{
    public ?int $control_id = null;

    public ?Control $ControlModel = null;

    public function loadControlContext(): void
    {
        $this->control_id = request()->route('control');

        $this->ControlModel = Control::findOrFail($this->control_id);
    }
}
