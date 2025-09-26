<?php

namespace App\Policies;

use App\Models\AuditNatureOfControl;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuditNatureOfControlPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_audit::nature::of::control');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AuditNatureOfControl $auditNatureOfControl): bool
    {
        return $user->can('view_audit::nature::of::control');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_audit::nature::of::control');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AuditNatureOfControl $auditNatureOfControl): bool
    {
        return $user->can('update_audit::nature::of::control');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AuditNatureOfControl $auditNatureOfControl): bool
    {
        return $user->can('delete_audit::nature::of::control');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_audit::nature::of::control');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, AuditNatureOfControl $auditNatureOfControl): bool
    {
        return $user->can('force_delete_audit::nature::of::control');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_audit::nature::of::control');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, AuditNatureOfControl $auditNatureOfControl): bool
    {
        return $user->can('restore_audit::nature::of::control');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_audit::nature::of::control');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, AuditNatureOfControl $auditNatureOfControl): bool
    {
        return $user->can('replicate_audit::nature::of::control');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_audit::nature::of::control');
    }
}
