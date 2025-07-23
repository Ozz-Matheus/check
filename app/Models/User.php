<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasUserLogic;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasPanelShield, HasRoles, HasUserLogic, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function docs()
    {
        return $this->hasMany(Doc::class);
    }

    public function docVersions()
    {
        return $this->hasMany(DocVersion::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class, 'responsible_by_id');
    }

    public function actionTasks()
    {
        return $this->hasMany(ActionTask::class, 'responsible_by_id');
    }

    public function subProcesses()
    {
        return $this->belongsToMany(SubProcess::class, 'user_has_sub_processes');
    }

    // public function registeredActions()
    // {
    //     return $this->hasMany(Action::class, 'registered_by_id');
    // }

    // public function ownedSubProcesses()
    // {
    //     return $this->hasMany(SubProcess::class, 'user_id');
    // }

    public function audits()
    {
        return $this->belongsToMany(Audit::class, 'audit_has_users');
    }

    public function findings()
    {
        return $this->hasMany(Finding::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accesores / Métodos útiles
    |--------------------------------------------------------------------------
    */

    // Metodos para el Acceso.

    public function isActive(): bool
    {
        return (bool) $this->active;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->hasRole('super_admin')) {
            return true;
        }

        if (! $this->isActive()) {
            Auth::logout();

            Notification::make()
                ->title(__('Account deactivated'))
                ->body(__('Your account has been deactivated. Contact the administrator.'))
                ->danger()
                ->persistent()
                ->send();

            return false;
        }

        return true;
    }
}
