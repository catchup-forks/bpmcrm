<?php
namespace App\Http\Middleware;

use Closure;
use Igaster\LaravelTheme\Facades\Theme;
use Illuminate\Http\Request;

/**
 * Class SetSkin
 * @package app\Http\Middleware
 *
 * Sets the skin requested by the request
 */
final class SetSkin
{

    /**
     * Handle request. If the request has a route parameter called skin, set the skin property in our view config
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Grab the skin parameter
        $skin = $request->route('skin');

        if ($skin) {
            Theme::set($skin);
        }

        // Process next
        return $next($request);
    }
}
