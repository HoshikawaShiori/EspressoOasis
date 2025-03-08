<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Log;
use App\Models\LoginAttempt;
use Illuminate\Support\Facades\Auth;

class LogFailedLogin
{
    /**
     * Handle the event.
     */
    public function handle(Failed $event)
    {
       
        LoginAttempt::create([
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status' => 'failed',
        ]);

        Log::warning("Failed login attempt from IP: " . request()->ip());
    }
}

