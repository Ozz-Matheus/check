<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Supplier extends User
{
    protected $table = 'users';

    protected $guard_name = 'web';

    /**
     * Este método es CLAVE: Le dice a Laravel que cuando busque en model_has_roles
     * use 'App\Models\User' en lugar de 'App\Models\Supplier'
     */
    public function getMorphClass()
    {
        return User::class;
    }

    protected static function booted(): void
    {
        parent::booted();

        // Scope global que filtra solo usuarios con rol 'supplier'
        static::addGlobalScope('supplier', function (Builder $query) {
            $query->whereHas('roles', function (Builder $q) {
                $q->where('name', 'supplier');
            });
        });

        // Al crear un nuevo Supplier, automáticamente asigna el rol
        static::created(function (User $model) {
            if (! $model->hasRole('supplier')) {
                $model->assignRole('supplier');
            }
        });
    }
}
