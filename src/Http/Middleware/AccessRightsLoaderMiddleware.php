<?php

namespace Designitgmbh\MonkeyAccess\Http\Middleware;

use Designitgmbh\MonkeyAccess\AccessRight\AccessRightLaravelLoader;
use Closure;
use Auth;

class AccessRightsLoaderMiddleware
{
    public function handle($request, Closure $next)
    {
    	if(Auth::check())
    	{
    		AccessRightLaravelLoader::load(Auth::user());
		}

        return $next($request);
    }
}