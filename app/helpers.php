<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 01/04/2019
 * Time: 20:20
 */

if(!function_exists('routeTenant')){
    /**
     * Generate the URL to a named route.
     *
     * @param array|string $name
     * @param array $params
     * @param bool $absolute
     * @return string
     */
    function routeTenant($name, $params = [], $absolute = true){
        $tenantManager = app(\App\Tenant\TenantManager::class);
        return route($name, $params+[ config('tenant.route_param') => $tenantManager->routeParam() ], $absolute);
    }
}
