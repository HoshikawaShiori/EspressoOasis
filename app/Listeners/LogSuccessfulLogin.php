<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use App\Models\LoginAttempt;
use Illuminate\Support\Facades\Auth;

class LogSuccessfulLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {

        $user = $event->user;

        LoginAttempt::create([
            'user_id' => request()->user()->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status' => 'success',
        ]);

        Log::info("User {$user->id} successfully logged in from IP: " . request()->ip());
    }
}

