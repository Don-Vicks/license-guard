<?php

// app/Http/Middleware/EnsureOnlyAdminCanAccessAdminDashboard.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class EnsureOnlyAdminCanAccessAdminDashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Gate::allows('access-admin-panel')) {
            return redirect('/user');
        }

        return $next($request);
    }
}
