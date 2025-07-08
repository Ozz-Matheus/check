<?php

namespace App\Traits;

use App\Models\Action;

trait HasActionContext
{
    public ?int $action_id = null;

    public ?Action $actionModel = null;

    public function loadActionContext(): void
    {
        $this->action_id = request()->route('action');

        $this->actionModel = Action::findOrFail($this->action_id);
    }
}
