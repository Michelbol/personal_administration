<?php

namespace Tests\Feature\App\Http\Controllers;

use App\QueenGame;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TestCase;

class QueenGameControllerTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSave()
    {
        $dataQueen = factory(QueenGame::class)->make()->toArray();
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $response = $this->post("$tenant->sub_domain/queen-game", $dataQueen);

        $response->assertStatus(200);
    }
}
