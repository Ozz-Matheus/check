<?php

namespace App\Models;

use App\Support\AppNotifier;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Headquarter extends Model
{
    protected $fillable = ['name', 'acronym'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (Headquarter $hq) {

            if ($hq->users()->exists()) {

                AppNotifier::error(
                    __('Headquarter'),
                    __('This Headquarter has assigned users and cannot be deleted.'),
                );

                throw new Halt;
            }

        });
    }
}
