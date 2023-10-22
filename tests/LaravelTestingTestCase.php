<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

abstract class LaravelTestingTestCase extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;
    protected $user;

    public function getRandomUser()
    {
        $this->user = User::get()->random();

        return $this->user;
    }

    public function getUserRandomArticle()
    {
        return $this->user->articles->random();
    }

    public function getAnotherRandomUser()
    {
        return User::where('id', '<>', $this->user->id)->get()->random();
    }

    public function getRandomArticleData()
    {
        return [
            "title" => $this->faker->sentence,
            "body" => $this->faker->paragraph
        ];
    }
}