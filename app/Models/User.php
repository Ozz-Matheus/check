<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Support\AppNotifier;
use App\Traits\HasUserLogic;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasPanelShield, HasRoles, HasUserLogic, Notifiable, SoftDeletes;

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
        'headquarter_id',
        'view_all_headquarters',
        'interact_with_all_headquarters',
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
            'view_all_headquarters' => 'boolean',
            'interact_with_all_headquarters' => 'boolean',
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

    public function accessToAdditionalUsers()
    {
        return $this->belongsToMany(Doc::class, 'docs_has_confidential_users', 'doc_id', 'user_id');
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

    public function leaderOf()
    {
        return $this->belongsToMany(SubProcess::class, 'users_lead_subprocesses');
    }

    public function headquarter()
    {
        return $this->belongsTo(Headquarter::class);
    }

    public function leadSubProcesses()
    {
        return $this->belongsToMany(SubProcess::class, 'users_lead_subprocesses', 'user_id', 'sub_process_id');
    }

    // public function registeredActions()
    // {
    //     return $this->hasMany(Action::class, 'registered_by_id');
    // }

    // public function ownedSubProcesses()
    // {
    //     return $this->hasMany(SubProcess::class, 'user_id');
    // }

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
        // 1️⃣ Super Admin
        if ($this->hasRole('super_admin')) {
            return true;
        }

        // 2️⃣ Evaluamos condiciones de bloqueo
        // match(true) buscará la primera condición que se cumpla
        $failure = match (true) {
            tenant()?->is_active === false => [
                'Workspace Deactivated',
                'This workspace is currently deactivated. Contact the administrator.',
            ],
            ! $this->isActive() => [
                'Account Deactivated',
                'Your account has been deactivated. Contact the administrator.',
            ],
            default => null, // Si todo está bien
        };

        // 3️⃣ Ejecutamos el cierre de sesión una sola vez
        if ($failure) {

            Auth::logout();

            AppNotifier::error($failure[0], $failure[1], true);

            return false;
        }

        return true;
    }
}
