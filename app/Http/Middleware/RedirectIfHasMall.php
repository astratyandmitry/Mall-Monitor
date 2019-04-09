<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfHasMall
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            return redirect()->to(route_return('auth.signin'));
        }

        if (Auth::guard($guard)->user()->mall_id) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }

}
