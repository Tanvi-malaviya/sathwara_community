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
            
            // Check if user is Administrator or has approved status
            if ($user->hasRole('Administrator')) {
                return $next($request);
            }

            if ($user->status !== 'approved') {
                if (!$request->is('account-status') && !$request->is('logout') && !$request->routeIs('logout')) {
                    return redirect()->route('account.status');
                }
            }
        }

        return $next($request);
    }
}
