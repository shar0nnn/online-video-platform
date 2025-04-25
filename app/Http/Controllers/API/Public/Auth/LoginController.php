<?php

namespace App\Http\Controllers\API\Public\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->validated())) {
            return response()->json(['message' => 'Wrong email or password.'], 401);
        }
        $user = User::query()->where('email', $request->email)->first();

        return response()->json([
            'message' => 'Logged in successfully.',
            'token' => $user->createToken('auth-token')->plainTextToken,
        ]);
    }

//    public function logout(Request $request): RedirectResponse
//    {
//        Auth::logout();
//        $request->session()->invalidate();
//        $request->session()->regenerateToken();
//
//        return redirect()->route('home');
//    }
}
