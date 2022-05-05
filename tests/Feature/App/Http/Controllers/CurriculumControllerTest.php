<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Requests\ContactFormCurriculumRequest;
use Biscolab\ReCaptcha\Facades\ReCaptcha;
use Faker\Generator;
use Mail;
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
            ->assertViewIs('curriculum')
            ->assertStatus(200);
    }

    public function testContact()
    {
        ReCaptcha::
            shouldReceive('validate')
            ->andReturn(true);
        Mail
            ::shouldReceive('send')
            ->once();

        $response = $this->post('/contact', factory(ContactFormCurriculumRequest::class)->make()->toArray());
        $response
            ->assertRedirect('/')
            ->assertSessionHas('message');

    }
}
