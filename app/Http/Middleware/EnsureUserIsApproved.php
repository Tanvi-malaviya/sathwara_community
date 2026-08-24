<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Check if user is Administrator or Sub Admin
            if ($user->hasRole('Administrator') || $user->hasRole('Sub Admin')) {
                return $next($request);
            }

            if ($user->status !== 'approved' || $user->account_status === 'close') {
                if (!$request->is('account-status') && !$request->is('logout') && !$request->routeIs('logout')) {
                    return redirect()->route('account.status');
                }
            }
        }

        return $next($request);
    }
}
