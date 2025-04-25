<?php

namespace App\Http\Controllers\API\Public\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to Google’s OAuth page.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google.
     */
    public function callback(): RedirectResponse
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return redirect()->route('login')->withErrors('Google authentication failed.');
        }
//        dd($user);

        $existingUser = User::query()->where('email', $user->email)->first();

        if ($existingUser) {
            Auth::login($existingUser);
        } else {
            $newUser = User::query()->updateOrCreate([
                'email' => $user->email
            ], [
                'name' => $user->name,
                'password' => bcrypt(Str::random()),
                'email_verified_at' => now()
            ]);

            Auth::login($newUser);
        }

        return redirect()->route('admin.dashboard');
    }
}
