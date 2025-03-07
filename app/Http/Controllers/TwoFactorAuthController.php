<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use App\Models\User;
use Illuminate\Support\Str;

class TwoFactorAuthController extends Controller
{
    public function show2faForm()
    {
        return view('auth.2fa');
    }

    public function enable2fa(Request $request)
    {
        $google2fa = app('pragmarx.google2fa');

        // Generate the secret key
        $secret = $google2fa->generateSecretKey();

        // Get user
        $user = Auth::user();

        // Generate the QR code
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        // Pass the secret and QR code URL to the view
        return view('auth.enable-2fa', [
            'secret' => $secret,
            'qrCodeUrl' => $qrCodeUrl
        ]);
    }

    public function confirm2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|numeric',
            'secret' => 'required|string'
        ]);

        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($request->secret, $request->code);

        if ($valid) {
            $user = Auth::user();
            $user->two_factor_secret = $request->secret;
            $user->two_factor_enabled = true;
            $user->two_factor_recovery_codes = json_encode($this->generateRecoveryCodes());
            $user->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => '2FA has been enabled successfully.'
                ]);
            }

            return redirect()->route('user.profile')->with('success', '2FA has been enabled successfully.');
        }

        if ($request->ajax()) {
            return response()->json([
                'error' => 'Invalid verification code. Please try again.'
            ], 422);
        }

        return back()->with('error', 'Invalid verification code. Please try again.');
    }

    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|numeric'
        ]);

        $user = Auth::user();
        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->code);

        if ($valid) {
            $user->two_factor_secret = null;
            $user->two_factor_enabled = false;
            $user->two_factor_recovery_codes = null;
            $user->save();

            return redirect()->route('user.profile')->with('success', '2FA has been disabled.');
        }

        return back()->with('error', 'Invalid verification code.');
    }

    protected function generateRecoveryCodes()
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = Str::random(10);
        }
        return $codes;
    }

    public function verify2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|numeric'
        ]);

        // If user is not logged in but has 2FA session
        if (!Auth::check() && session()->has('2fa_user_id')) {
            $user = User::find(session('2fa_user_id'));
            if (!$user) {
                return redirect()->route('user.signin')->with('error', 'Please login again.');
            }
        } else {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('user.signin')->with('error', 'Please login again.');
            }
        }

        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->code);

        if ($valid) {
            if (!Auth::check()) {
                Auth::login($user);
            }
            session(['2fa_verified' => true]);
            session()->forget('2fa_user_id');

            return redirect()->intended(route('coffee.shop'));
        }

        return back()->with('error', 'Invalid verification code.');
    }
}
