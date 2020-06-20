<?php

namespace Tests\Feature\App\Http\Controllers;

use Tests\TestCase;

class BiosControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get("/bios");

        $response
            ->assertStatus(200)
            ->assertViewIs('bios.index');
    }
}
