<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class SupplierPortal extends SupplierIssue
{
    protected $table = 'supplier_issues';

    public function getMorphClass()
    {
        return SupplierIssue::class;
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        parent::booted();

        // Global scope to filter issues for the logged-in supplier.
        static::addGlobalScope('supplier', function (Builder $query) {
            $query->where('supplier_id', auth()->id())
                // and where the status is not 'open'.
                ->whereHas('status', function (Builder $q) {
                    $q->where('context', 'supplier_issue')
                        ->where('title', '!=', 'open');
                });
        });
    }
}
