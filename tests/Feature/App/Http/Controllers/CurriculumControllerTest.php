<?php

namespace Tests\Feature\App\Http\Controllers;

use Tests\TestCase;

class CurriculumControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCurriculum()
    {
        $response = $this->get('/');

        $response
            ->assertViewIs('welcome')
            ->assertStatus(200);
    }
}
