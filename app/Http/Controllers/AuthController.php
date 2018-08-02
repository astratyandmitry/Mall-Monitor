<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class AuthController extends Controller
{

    use AuthenticatesUsers;


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
     * @return void
     */
    protected function validateLogin(Request $request): void
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }


    /**
     * @return string
     */
    public function redirectPath(): string
    {
        if (isset($_GET['return'])) {
            return $_GET['return'];
        }

        return $this->redirectTo;
    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function signout(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->guard()->logout();

        return redirect($this->redirectTo);
    }

}
