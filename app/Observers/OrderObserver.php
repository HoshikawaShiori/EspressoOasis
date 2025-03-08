<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;

class OrderObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(Order $order): void
    {
        $this->logAuditTrail('create', $order, null, $order->toArray());
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(Order $order): void
    {
        $originalData = $order->getOriginal();
        $this->logAuditTrail('update', $order, $originalData, $order->toArray());
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(Order $order): void
    {
        $this->logAuditTrail('delete', $order, $order->toArray(), null);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(Order $order): void
    {

        $this->logAuditTrail('restore', $order, null, $order->toArray());
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        
        $this->logAuditTrail('force_delete', $order, $order->toArray(), null);
    }

    /**
     * Helper function to log the audit trail.
     */
    protected function logAuditTrail($action, $order, $oldData, $newData)
    {
        AuditTrail::create([
            'model_type' => get_class($order),
            'model_id' => $order->id,
            'action' => $action,
            'old_data' => $oldData,
            'new_data' => $newData,
            'user_id' => Auth::id(), 
        ]);
    }
}

