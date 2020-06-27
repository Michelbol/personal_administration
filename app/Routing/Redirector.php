<?php
/**
 * Created by PhpStorm.
 * User: Desenvolvimento3
 * Date: 28/02/2019
 * Time: 11:16
 */

namespace App\Routing;

use App\Tenant\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector as RedirectorLaravel;

class Redirector extends RedirectorLaravel
{
    /**
     * @param $name
     * @param array $params
     * @param int $status
     * @param array $headers
     * @return RedirectResponse
     */
    public function routeTenant($name, $params = [], $status = 302, $headers = []){
        $tenantManager = app(TenantManager::class);
        $tenantParam = $tenantManager->routeParam();
        return $this->route($name, $params + [
                config('tenant.route_param') => $tenantParam
            ], $status, $headers);
    }
}
