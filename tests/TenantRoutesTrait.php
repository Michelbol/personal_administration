<?php

namespace Tests;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\TestResponse;

trait TenantRoutesTrait
{
    protected $tenant;

    /**
     * @param $uri
     * @param array $headers
     * @return TestResponse
     */
    public function get($uri, array $headers = [])
    {
        $tenant = $this->getTenant();

        $server = $this->transformHeadersToServerVars($headers);

        return $this->call('GET', "$tenant->sub_domain/$uri", [], [], [], $server);
    }

    /**
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return TestResponse
     */
    public function post($uri, array $data = [], array $headers = [])
    {
        $tenant = $this->getTenant();

        $server = $this->transformHeadersToServerVars($headers);

        return $this->call('POST', "$tenant->sub_domain/$uri", $data, [], [], $server);
    }

    /**
     * Visit the given URI with a PUT request.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return TestResponse
     */
    public function put($uri, array $data = [], array $headers = [])
    {
        $tenant = $this->getTenant();

        $server = $this->transformHeadersToServerVars($headers);

        return $this->call('PUT', "$tenant->sub_domain/$uri", $data, [], [], $server);
    }

    /**
     * Visit the given URI with a DELETE request.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return TestResponse
     */
    public function delete($uri, array $data = [], array $headers = [])
    {
        $tenant = $this->getTenant();

        $server = $this->transformHeadersToServerVars($headers);

        return $this->call('DELETE', "$tenant->sub_domain/$uri", $data, [], [], $server);
    }

    public function getTenant()
    {
        return Tenant::whereSubDomain('souza')->first();
    }
}
