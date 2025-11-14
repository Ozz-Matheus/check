<?php

namespace App\Traits;

use App\Models\Headquarter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class HeadquarterScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->check()) {

            $user = auth()->user();

            if (! $user->hasRole('super_admin') && (bool) $user->view_all_headquarters === false) {

                $builder->where($model->getTable().'.headquarter_id', $user->headquarter_id);

            }
        }
    }
}

trait BelongsToHeadquarter
{
    public static function bootBelongsToHeadquarter(): void
    {
        static::addGlobalScope(new HeadquarterScope);

        static::creating(function ($model) {
            if (auth()->check() && empty($model->headquarter_id)) {
                $model->headquarter_id = auth()->user()->headquarter_id;
            }
        });
    }

    public function headquarter()
    {
        return $this->belongsTo(Headquarter::class);
    }
}
