<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function showLogin()
    {
        return view('page.auth.sign-in');
    }

    public function login(Request $request)
    {
        $data = $this->userService->validateLoginUser($request);

        if (auth()->attempt($data)) {
            $role = auth()->user()->role;
            return redirect()->intended($this->userService->redirectDuringLogin($role));
        }
        return redirect()->back()->withErrors([
            'login' => 'Неверный логин или пароль.',
        ]);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
