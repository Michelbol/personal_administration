<?php

namespace App\Scopes;

use App\Tenant\TenantManager;
use Illuminate\Database\Eloquent\Model;

trait TenantModels
{

    protected static function boot(){

        parent::boot();

        static::addGlobalScope(new TenantScopes());

        static::creating(function(Model $model){
            $tenant = new TenantManager();
            if($tenant->getTenant()){
                $model->tenant_id = $tenant->getTenant()->id;
            }
        });
    }
}