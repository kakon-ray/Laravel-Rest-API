<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard=null , $redirectToRoute = null)
    {
        // dd($guard, $redirectToRoute, URL::route($redirectToRoute ?: 'admin.verification.notice'));

        if (! $request->user('admin')->email_verified_at || ($request->user($guard) instanceof MustVerifyEmail && ! $request->user($guard)->hasVerifiedEmail())) {
            return redirect('admin/verify-email');
    }

    return $next($request);
    }
}
