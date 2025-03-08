<?php

namespace App\Observers;

use App\Models\User;
use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->logAuditTrail('create', $user, null, $user->toArray());
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $originalData = $user->getOriginal();
        $this->logAuditTrail('update', $user, $originalData, $user->toArray());
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->logAuditTrail('delete', $user, $user->toArray(), null);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {

        $this->logAuditTrail('restore', $user, null, $user->toArray());
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        
        $this->logAuditTrail('force_delete', $user, $user->toArray(), null);
    }

    /**
     * Helper function to log the audit trail.
     */
    protected function logAuditTrail($action, $user, $oldData, $newData)
    {
        AuditTrail::create([
            'model_type' => get_class($user),
            'model_id' => $user->id,
            'action' => $action,
            'old_data' => $oldData,
            'new_data' => $newData,
            'user_id' => Auth::id(), 
        ]);
    }
}

