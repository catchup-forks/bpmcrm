<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \Illuminate\Auth\Access\AuthorizationException;

final class Authorize
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user->is_administrator) {
            return $next($request);
        }

        // Get the action that the user is requesting
        $permission = $request->route()->action['as'];

        // Remove the api route prefix since they will have the
        // same permissions as the web routes.
        $permission = preg_replace('/^api\./', '', (string) $permission);
        // At this point we should have already checked if the
        // user is logged in so we can assume $request->user()
        if ($request->user()->hasPermission($permission)) {
            return $next($request);
        }

        if ($this->allowIndexForShow($permission, $request)) {
            return $next($request);
        }
        else {
            throw new AuthorizationException("Not authorized: " . $permission);
        }
    }

    /**
     * If the user has show permission, assume they
     * have index/list permission as well.
     */
    private function allowIndexForShow(string|array|null $permission, Request $request)
    {
        if(preg_match('/^(.*)\.index$/', $permission, $match)) {
            return $request->user()->hasPermission($match[1] . '.show');
        }
        return false;
    }
}
