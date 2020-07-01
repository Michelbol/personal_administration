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

class TenantScopes implements Scope
{

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $tenant = new TenantManager();
        if($tenant->getTenant()){
            $builder->where($model->getTable().'.tenant_id', $tenant->getTenant()->id);
        }

    }
}
