<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 01/04/2019
 * Time: 20:27
 */
return [
    'model' => \App\Models\Tenant::class,
    'field_name' => 'sub_domain',
    'foreign_key' => 'tenant_id',
    'route_param' => 'tenant',
    'subdomains_except' => [
        'master'
    ]
];