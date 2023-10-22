<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\LaravelTestingTestCase;
use Tests\TestCase;

class AuthControllerTest extends LaravelTestingTestCase
{
    /**
     * @test
     */
    public function it_returns_register_page()
    {
        $response = $this->get(route('register'));
        $response->assertViewIs('auth.register');
    }
    /**
     * @test
     */
    public function it_returns_login_page()
    {
        $response = $this->get(route('login'));
        $response->assertViewIs('auth.login');
    }
}