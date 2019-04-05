<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27/02/2019
 * Time: 22:49
 */

namespace App\Scopes;


use App\Tenant\TenantManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScopes implements Scope
{

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $tenant = new TenantManager();
        if($tenant->getTenant()){
            $builder->where('tenant_id', $tenant->getTenant()->id);
        }

    }
}