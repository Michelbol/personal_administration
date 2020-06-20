<?php

namespace Tests\Feature\App\Http\Middleware;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Str;
use Tests\SeedingTrait;
use Tests\TestCase;

class DefineAuthGuardTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testHandle()
    {

        $response = $this->get('/master');

        $response->assertStatus(200);

        $response = $this->get('/' . Str::random());

        $response->assertStatus(403);
    }
}
