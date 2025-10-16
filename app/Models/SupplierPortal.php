<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class SupplierPortal extends SupplierIssue
{
    protected $table = 'supplier_issues';

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('supplier', function (Builder $query) {

            $user = auth()->user();

            if ($user && ! $user->hasRole('super_admin')) {

                $query->forSupplier($user->id)
                    ->excludingOpenStatus();
            }
        });
    }

    /**
     * Scope para filtrar por proveedor específico
     */
    public function scopeForSupplier(Builder $query, int $supplierId): Builder
    {
        return $query->where('supplier_id', $supplierId);
    }

    /**
     * Scope para excluir issues con status 'open'
     */
    public function scopeExcludingOpenStatus(Builder $query): Builder
    {
        return $query->whereHas('status', function (Builder $q) {
            $q->where('context', 'supplier_issue')
                ->where('title', '!=', 'open');
        });
    }

    /**
     * Scope para incluir SOLO issues con status específico
     */
    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        return $query->whereHas('status', function (Builder $q) use ($status) {
            $q->where('context', 'supplier_issue')
                ->where('title', $status);
        });
    }
}
