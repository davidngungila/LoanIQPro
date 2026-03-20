<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TwoFactorToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TwoFactorController extends Controller
{
    /**
     * Display the 2FA challenge view.
     */
    public function create()
    {
        return view('auth.2fa-challenge');
    }

    /**
     * Handle the 2FA verification.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'digits:6'],
        ]);

        $user = Auth::user();

        // For demo purposes, check against session
        if (session('2fa_code') === $request->code) {
            session()->forget('2fa_code');
            
            // Mark all valid tokens as used
            TwoFactorToken::where('user_id', $user->id)
                ->valid()
                ->update(['used' => true]);

            $user->logActivity('2fa_verified', 'Two-factor authentication verified');

            // Redirect based on user role
            return $this->redirectBasedOnRole($user);
        }

        // Check against database tokens
        $token = TwoFactorToken::where('user_id', $user->id)
            ->valid()
            ->first();

        if ($token && Hash::check($request->code, $token->code)) {
            $token->update(['used' => true]);
            
            $user->logActivity('2fa_verified', 'Two-factor authentication verified');

            return $this->redirectBasedOnRole($user);
        }

        $user->logActivity('2fa_failed', 'Two-factor authentication failed');

        return back()->withErrors([
            'code' => 'The provided code is invalid or has expired.',
        ]);
    }

    /**
     * Resend 2FA code.
     */
    public function resend(Request $request)
    {
        $user = Auth::user();

        // Invalidate previous codes
        TwoFactorToken::where('user_id', $user->id)
            ->valid()
            ->update(['used' => true]);

        $this->generateTwoFactorCode($user);

        $user->logActivity('2fa_resend', 'Two-factor code resent');

        return back()->with('status', 'A new verification code has been sent.');
    }

    /**
     * Generate two factor authentication code
     */
    private function generateTwoFactorCode($user)
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        TwoFactorToken::create([
            'user_id' => $user->id,
            'code' => Hash::make($code),
            'type' => 'app',
            'expires_at' => now()->addMinutes(10),
        ]);

        // In a real application, you would send this code via SMS or email
        // For demo purposes, we'll store it in session
        session(['2fa_code' => $code]);
    }

    /**
     * Redirect user based on their highest role
     */
    private function redirectBasedOnRole($user)
    {
        $highestRole = $user->roles()->orderBy('level', 'desc')->first();
        
        if (!$highestRole) {
            return redirect()->route('dashboard');
        }

        return match($highestRole->slug) {
            'super-admin' => redirect()->route('dashboard.global'),
            'admin' => redirect()->route('dashboard.branch'),
            'loan-officer' => redirect()->route('dashboard.my-loans'),
            'accountant' => redirect()->route('dashboard.financial'),
            'collector' => redirect()->route('dashboard.cases'),
            'auditor' => redirect()->route('dashboard.compliance'),
            'customer' => redirect()->route('dashboard.summary'),
            'guarantor' => redirect()->route('dashboard.guaranteed'),
            'it-support' => redirect()->route('dashboard.health'),
            default => redirect()->route('dashboard'),
        };
    }
}
