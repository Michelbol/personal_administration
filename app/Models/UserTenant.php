<?php

namespace App\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserTenant
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $tenant_id
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static Builder|UserTenant newModelQuery()
 * @method static Builder|UserTenant newQuery()
 * @method static Builder|UserTenant query()
 * @method static Builder|UserTenant whereCreatedAt($value)
 * @method static Builder|UserTenant whereEmail($value)
 * @method static Builder|UserTenant whereEmailVerifiedAt($value)
 * @method static Builder|UserTenant whereId($value)
 * @method static Builder|UserTenant whereName($value)
 * @method static Builder|UserTenant wherePassword($value)
 * @method static Builder|UserTenant whereRememberToken($value)
 * @method static Builder|UserTenant whereTenantId($value)
 * @method static Builder|UserTenant whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
