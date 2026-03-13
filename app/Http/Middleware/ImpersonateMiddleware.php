<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ImpersonateMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->has('impersonate_id')) {
            $user = User::find($request->session()->get('impersonate_id'));
            
            if ($user) {
                // Set the current user in the Auth guard for the request duration
                Auth::setUser($user);
            }
        }

        return $next($request);
    }
}
