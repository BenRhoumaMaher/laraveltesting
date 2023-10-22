<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Faker\Factory as Faker;
use Tests\LaravelTestingTestCase;
use Tests\TestCase;

class AuthorizedLoggedInControllerTest extends LaravelTestingTestCase
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
    public function it_allows_logged_in_users_to_create_new_articles()
    {
        $user = $this->getRandomUser();

        $response = $this->actingAs($user)->get(route('create_new_article'));

        $response->assertViewIs('articles.create');
    }

    /**
     * @test
     */
    public function it_allows_logged_in_users_to_save_new_articles()
    {
        $user = $this->getRandomUser();

        $totalNumberOfArticlesBefore = Article::count();

        $data = $this->getRandomArticleData();

        $response = $this->actingAs($user)->post(route('save_new_article'), $data);

        $lastInsertedArticleDB = Article::orderBy('id', 'desc')->first();
        $this->assertEquals($lastInsertedArticleDB->title, $data['title'], 'title of the saved article
        is different from the title we used');
        $this->assertEquals($lastInsertedArticleDB->body, $data['body'], 'body of the saved article
        is different from the body we used');

        $this->assertEquals($lastInsertedArticleDB->author_id, $user->id, 'owner of the saved article
        is different from the user we used');

        $totalNumberOfArticlesAfter = Article::count();
        $this->assertEquals($totalNumberOfArticlesAfter, $totalNumberOfArticlesBefore + 1, 'the number
        of total article is supposed to be incremented by 1');

        $response->assertRedirect(route('view_article', ['article' => $lastInsertedArticleDB->id]));

    }

    /**
     * @test
     */
    public function it_allows_owner_of_an_article_to_edit_it()
    {
        $user = $this->getRandomUser();
        $article = $this->getUserRandomArticle();


        $response = $this->actingAs($user)->get(route('edit_article', ['article' => $article->id]));

        $response->assertViewIs('articles.edit');

        $returnedArticle = $response->original->article;
        $this->assertEquals($article->id, $returnedArticle->id, 'the returned article is different from
        the one we want to edit');
    }

    /**
     * @test
     */

    public function it_allows_owner_of_an_article_to_save_edits()
    {
        $user = $this->getRandomUser();
        $article = $this->getUserRandomArticle();
        $totalNumberOfArticlesBefore = Article::count();

        $articleNewData = $this->getRandomArticleData();

        $response = $this->actingAs($user)->post(
            route('update_article', ['article' => $article->id]),
            $articleNewData
        );

        // get a fresh copy of the article
        $article->refresh();

        $this->assertEquals($article->title, $articleNewData['title'], "the title of the article
        wasn't updated");
        $this->assertEquals($article->body, $articleNewData['body'], "the title of the article
        wasn't updated");
        $this->assertEquals($article->author_id, $user->id, "the article was assigned to
        another user");

        $totalNumberOfArticlesAfter = Article::count();
        $this->assertEquals(
            $totalNumberOfArticlesAfter,
            $totalNumberOfArticlesBefore,
            "the number of total article is supposed to stay the same"
        );
        // ensure that we are redirected to the same article after updating it
        $response->assertRedirect(route('view_article', ['article' => $article->id]));
    }

    /**
     * @test
     */
    public function it_allows_owner_of_an_article_to_delete_it()
    {
        $user = $this->getRandomUser();
        $article = $this->getUserRandomArticle();

        $totalNumberOfArticlesBefore = Article::count();
        $response = $this->actingAs($user)->get(route('delete_article', ['article' => $article->id]));

        $article->fresh();
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);

        $totalNumberOfArticlesAfter = Article::count();
        $this->assertEquals($totalNumberOfArticlesAfter, $totalNumberOfArticlesBefore - 1, 'the number
        of article should decrement by 1');

        $response->assertRedirect(route('get_all_articles'));
    }

}