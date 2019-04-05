<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27/02/2019
 * Time: 23:33
 */
namespace App\Tenant;

class TenantManager{

    private $tenant;

    public function routeParam(){
        return \Request::route(config('tenant.route_param'));
    }

    public function isSubdomainException(){
        $tenantParam = $this->routeParam();
        return $tenantParam && in_array($tenantParam,config('tenant.subdomains_except'))
            ?true:false;
    }

    public function getTenant(){
        if(!$this->tenant){
            $model = config('tenant.model');
            $this->tenant = $model
                ::where(config('tenant.field_name'),$this->routeParam())
                ->first();
        }
        return $this->tenant;
    }
}