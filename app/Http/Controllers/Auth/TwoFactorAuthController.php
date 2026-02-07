<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthController extends Controller
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Show the 2FA challenge form.
     */
    public function create(Request $request)
    {
        if (! $request->session()->has('login.id')) {
            return redirect()->route('login');
        }

        return Inertia::render('Auth/TwoFactorChallenge');
    }

    /**
     * Verify the 2FA code and complete login.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => ['nullable', 'string'],
            'recovery_code' => ['nullable', 'string'],
        ]);

        $userId = $request->session()->get('login.id');
        $remember = $request->session()->get('login.remember', false);

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (! $user) {
            return redirect()->route('login');
        }

        // Try with regular code
        if ($request->code) {
            $valid = $this->google2fa->verifyKey(
                decrypt($user->two_factor_secret),
                $request->code
            );

            if (! $valid) {
                throw ValidationException::withMessages([
                    'code' => ['Le code est invalide.'],
                ]);
            }
        }
        // Try with recovery code
        elseif ($request->recovery_code) {
            $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);

            if (! in_array($request->recovery_code, $recoveryCodes)) {
                throw ValidationException::withMessages([
                    'recovery_code' => ['Le code de récupération est invalide.'],
                ]);
            }

            // Remove used recovery code
            $recoveryCodes = array_values(array_diff($recoveryCodes, [$request->recovery_code]));
            $user->two_factor_recovery_codes = encrypt(json_encode($recoveryCodes));
            $user->save();
        } else {
            throw ValidationException::withMessages([
                'code' => ['Veuillez fournir un code.'],
            ]);
        }

        // Clear session data and log in
        $request->session()->forget(['login.id', 'login.remember']);

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Show 2FA setup page.
     */
    public function showSetup(Request $request)
    {
        $user = $request->user();

        // Generate new secret if not exists or not confirmed
        if (! $user->two_factor_secret || ! $user->two_factor_confirmed_at) {
            $secret = $this->google2fa->generateSecretKey();
            $user->two_factor_secret = encrypt($secret);
            $user->save();
        } else {
            $secret = decrypt($user->two_factor_secret);
        }

        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return Inertia::render('Auth/TwoFactorSetup', [
            'secret' => $secret,
            'qrCodeUrl' => $qrCodeUrl,
            'enabled' => (bool) $user->two_factor_confirmed_at,
            'recoveryCodes' => $user->two_factor_recovery_codes
                ? json_decode(decrypt($user->two_factor_recovery_codes), true)
                : [],
        ]);
    }

    /**
     * Enable 2FA for the user.
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();
        $secret = decrypt($user->two_factor_secret);

        $valid = $this->google2fa->verifyKey($secret, $request->code);

        if (! $valid) {
            throw ValidationException::withMessages([
                'code' => ['Le code est invalide.'],
            ]);
        }

        // Generate recovery codes
        $recoveryCodes = [];
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = strtoupper(bin2hex(random_bytes(5)));
        }

        $user->two_factor_confirmed_at = now();
        $user->two_factor_recovery_codes = encrypt(json_encode($recoveryCodes));
        $user->save();

        return back()->with('status', 'two-factor-enabled');
    }

    /**
     * Disable 2FA for the user.
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $user->two_factor_secret = null;
        $user->two_factor_confirmed_at = null;
        $user->two_factor_recovery_codes = null;
        $user->save();

        return back()->with('status', 'two-factor-disabled');
    }

    /**
     * Regenerate recovery codes.
     */
    public function regenerateRecoveryCodes(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        $recoveryCodes = [];
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = strtoupper(bin2hex(random_bytes(5)));
        }

        $user->two_factor_recovery_codes = encrypt(json_encode($recoveryCodes));
        $user->save();

        return back()->with('status', 'recovery-codes-regenerated');
    }
}
