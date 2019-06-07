<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class AuthController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function signin(): \Illuminate\View\View
    {
        $this->setTitle('Вход');

        return view('auth.signin', $this->withData());
    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        if ( ! $request->email || ! $request->password || ! $user = User::where('email', $request->email)->first()) {
            return back()->withInput($request->only(['email']))->withErrors(['Вы указали неверные данные авторизации']);
        }

        if ( ! \Hash::check($request->password, $user->password)) {
            return back()->withInput($request->only(['email']))->withErrors(['Вы указали неверные данные авторизации']);
        }

        if ( ! $user->is_active) {
            return back()->withInput($request->only(['email']))->withErrors(['Ваш аккаунт неактивирован, проверьте почту']);
        }

        auth()->login($user);

        return redirect()->to($this->redirectPath());
    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate(Request $request): \Illuminate\Http\RedirectResponse
    {
        if ( ! $request->email || ! $request->activation_token || ! $user = User::where($request->only([
                'email',
                'activation_token'
            ]))->first()) {
            return redirect()->route('auth.signin')->withErrors(['Вы указали неверные данные активации']);
        }

        $user->update([
            'activation_token' => null,
            'is_active' => true,
        ]);

        return redirect()->route('auth.signin')->withErrors(['Ваш аккаунт активирован, теперь вы можете войти']);
    }


    /**
     * @return string
     */
    public function redirectPath(): string
    {
        if (isset($_GET['return'])) {
            return $_GET['return'];
        }

        return '/';
    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function signout(Request $request): \Illuminate\Http\RedirectResponse
    {
        auth()->logout();

        return redirect()->route('dashboard');
    }

}
