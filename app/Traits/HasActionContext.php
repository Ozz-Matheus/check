<?php

namespace App\Traits;

use App\Models\Action;

trait HasActionContext
{
    public ?int $action_id = null;

    public ?Action $ActionModel = null;

    public function loadActionContext(): void
    {
        $this->action_id = request()->route('action_id');

        $this->ActionModel = Action::findOrFail($this->action_id);
    }
}
