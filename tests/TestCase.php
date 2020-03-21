<?php

namespace Tests;

use App\Models\Tenant;
use App\Models\UserTenant;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Collection;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @return Collection
     */
    public function setUser()
    {
        $tenant = Tenant::first();
        $user = UserTenant::whereTenantId($tenant->id)->first();
        $this->actingAs($user);
        return collect(['user' => $user,'tenant' => $tenant]);
    }
}
