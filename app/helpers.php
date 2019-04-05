<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 01/04/2019
 * Time: 20:20
 */

if(!function_exists('routeTenant')){
    function routeTenant($name, $params = [], $absolute = true){
        $tenantManager = app(\App\Tenant\TenantManager::class);
        return route($name, $params+[ config('tenant.route_param') => $tenantManager->routeParam() ], $absolute);
    }
}