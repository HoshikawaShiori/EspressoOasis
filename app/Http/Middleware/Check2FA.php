<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class Check2FA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip 2FA check for these routes
        if ($request->routeIs('2fa.*') ||
            $request->routeIs('user.signin') ||
            $request->routeIs('user.signup') ||
            $request->routeIs('login.google') ||
            $request->routeIs('google.callback')) {
            return $next($request);
        }

        // If user is not logged in but has 2FA session
        if (!Auth::check() && session()->has('2fa_user_id')) {
            session(['show_2fa_modal' => true]);
            return redirect()->route('user.signin');
        }

        // If user is logged in and has 2FA enabled but not verified
        $user = Auth::user();
        if ($user && $user->two_factor_enabled && !session('2fa_verified')) {
            session(['2fa_user_id' => $user->id]);
            session(['show_2fa_modal' => true]);
            Auth::logout();
            return redirect()->route('user.signin');
        }

        return $next($request);
    }
}
