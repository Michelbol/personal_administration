<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use App\Scopes\TenantModels;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserTenant extends Authenticatable
{

    use Notifiable;
    use TenantModels;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'tenant_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
