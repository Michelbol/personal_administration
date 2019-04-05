<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 01/04/2019
 * Time: 20:25
 */

namespace App\Http\Middleware;


use App\Tenant\TenantManager;
use \Closure;
use Illuminate\Auth\AuthenticationException;

class DefineAuthGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        $tenant = new TenantManager();
        if($tenant->isSubdomainException()){
            config([
                'auth.defaults.guard' => 'web',
                'auth.defaults.passwords'=> 'users'
            ]);
        }
        if(!$tenant->getTenant() && !$tenant->isSubdomainException()){
            abort(403, 'Unauthorized action');
        }
        return $next($request);
    }
}