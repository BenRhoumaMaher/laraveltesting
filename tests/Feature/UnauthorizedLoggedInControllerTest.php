<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Faker\Factory as Faker;
use Tests\LaravelTestingTestCase;
use Tests\TestCase;

class UnauthorizedLoggedInControllerTest extends LaravelTestingTestCase
{
    protected $faker;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker::create();
    }
    /**
    * @test
    */
    public function it_doesnt_show_edit_button_to_non_owners_of_article()
    {

        $user = $this->getRandomUser();
        $anotherUser = $this->getAnotherRandomUser();

        $article = $this->getUserRandomArticle();

        $response = $this->actingAs($user)->get(route('view_article', ['article' => $article->id]));

        $response->assertSeeText('Edit Article');

        $response = $this->actingAs($anotherUser)->get(route('view_article', ['article' => $article->id]));

        $response->assertDontSeeText('Edit Article');
    }

    /**
     * @test
     */
    public function it_prevents_non_owner_of_an_article_from_editing_it()
    {
        $user = $this->getRandomUser();
        $anotherUser = $this->getAnotherRandomUser();

        $article = $this->getUserRandomArticle();

        $response = $this->get(route('edit_article', ['article' => $article->id]));

        $response = $this->actingAs($anotherUser)->get(route('edit_article', ['article' => $article->id]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function it_prevents_non_owner_of_an_article_from_saving_edits()
    {
        $user = $this->getRandomUser();
        $article = $this->getUserRandomArticle();

        $articleNewData = $this->getRandomArticleData();

        $anotherUser = $this->getAnotherRandomUser();

        $response = $this->actingAs($anotherUser)->json('POST', route('update_article', ['article' => $article->id]), $articleNewData);

        $response->assertForbidden();

        $response = $this->json('POST', route('update_article', ['article' => $article->id]), $articleNewData);

        $response->assertForbidden();
    }

    /**
    * @test
    */
    public function it_prevents_non_owner_of_an_article_from_deleting_it()
    {
        $user = $this->getRandomUser();
        $anotherUser = $this->getAnotherRandomUser();

        $article = $this->getUserRandomArticle();

        // non logged in user
        $response = $this->get(route('delete_article', ['article' => $article->id]));
        $response->assertForbidden();

        //logged in user
        $response = $this->actingAs($anotherUser)->get(route('delete_article', ['article' => $article->id]));
        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function it_prevents_users_from_saving_articles_with_short_titles_and_bodies()
    {
        $user = $this->getRandomUser();
        $article = $this->getUserRandomArticle();

        $articleNewData = [
            "title" => $this->faker->text(rand(5, 9)),
            "body" => $this->faker->text(rand(5, 9))
            ];


        $response = $this->actingAs($user)->json('POST', route('update_article', ['article' => $article->id]), $articleNewData);

        $response->assertJsonValidationErrors('title');
        $response->assertJsonValidationErrors('body');
    }

}