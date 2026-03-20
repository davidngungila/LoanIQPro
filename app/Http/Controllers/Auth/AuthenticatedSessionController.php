<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\TwoFactorToken;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        
        $credentials = [
            $loginField => $request->login,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            ActivityLog::create([
                'action' => 'login_failed',
                'description' => "Failed login attempt for {$request->login}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->withErrors([
                'login' => 'The provided credentials do not match our records.',
            ])->onlyInput('login');
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return back()->withErrors([
                'login' => 'Your account has been deactivated. Please contact administrator.',
            ])->onlyInput('login');
        }

        $request->session()->regenerate();

        // Log successful login
        $user->logActivity('login', 'User logged in successfully');

        // Check if 2FA is enabled
        if ($user->two_factor_secret) {
            $this->generateTwoFactorCode($user);
            return redirect()->route('2fa.challenge');
        }

        // Redirect based on user role
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            $user->logActivity('logout', 'User logged out');
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
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
