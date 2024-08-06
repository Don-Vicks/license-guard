<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;

class InstantiatePermissions
{
    /**
     * A flag to check if the gates have already been defined.
     *
     * @var bool
     */
    protected static $gatesDefined = false;

    public function handle(Request $request, Closure $next)
    {
        // Check if gates have already been defined
        if (!self::$gatesDefined) {
            // Define gates dynamically based on permissions
            $permissions = Permission::all();
            foreach ($permissions as $permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission->name);
                });
            }

            // Set the flag to true after defining gates
            self::$gatesDefined = true;
        }

        return $next($request);
    }
}
