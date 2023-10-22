<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\LaravelTestingTestCase;
use Tests\TestCase;

class ArticleControllerTest extends LaravelTestingTestCase
{
    /**
     * @test
     */
    public function it_allows_anyone_to_see_list_all_articles()
    {
        $response = $this->get(route('get_all_articles'));

        $response->assertSuccessful();

        $response->assertViewIs('articles.index');
        $response->assertViewHas('articles');
    }
    /**
     * @test
     */
    public function it_allows_anyone_to_see_individual_articles()
    {
        $user = $this->getRandomUser();
        $article = $this->getUserRandomArticle();

        $response = $this->get(route('view_article', ['article' => $article->id]));

        $response->assertViewIs('articles.view');
        $response->assertViewHas('article');

        $returnedArticle = $response->original->article;
        $this->assertEquals($article->id, $returnedArticle->id, "The returned article is different from the one we requested");
    }


}