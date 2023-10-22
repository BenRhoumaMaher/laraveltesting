<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\LaravelTestingTestCase;
use Tests\TestCase;

class UserControllerTest extends LaravelTestingTestCase
{
    /**
     * @test
     */
    public function it_allows_anyone_to_see_users_profiles()
    {
        $user =  $this->getRandomUser();

        $response = $this->get(route('show_user_profile', ['user' => $user->id]));

        $response->assertViewIs('users.show');
        $response->assertViewHas('user');

        $returnedUser = $response->original->user;

        $this->assertEquals($user->id, $returnedUser->id, "the returned user is different
        from the one we requested");
    }
    /**
     * @test
     */
    public function it_prevent_non_logged_in_users_from_creating_new_articles()
    {
        $response = $this->get(route('create_new_article'));
        $response->assertRedirect('login');
    }

}