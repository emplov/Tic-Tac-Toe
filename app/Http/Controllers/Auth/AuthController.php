<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function login()
    {
        return view('tictactoe.auth.login');
    }

    /**
     * @return Application|Factory|View
     */
    public function signUp()
    {
        return view('tictactoe.auth.register');
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('name', 'password');

        if (Auth::attempt($credentials, $request->has('remember_me'))) {
            return redirect('/');
        }

        return redirect()->back()->withErrors([
            'auth' => ['There is no such user.']
        ])->withInput([
            'name' => $credentials['name']
        ]);
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255|unique:users,name',
            'password' => 'required|confirmed',
        ]);

        User::query()->create([
            'name' => $request->get('name'),
            'password' => bcrypt($request->get('password')),
        ]);

        return redirect('/');
    }

    /**
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('auth.login');
    }
}
