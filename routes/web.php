<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;

Auth::routes();
Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'homepage'])->name('home');

Route::get('articles', [ArticleController::class, 'index'])->name('get_all_articles');
Route::get('new-article', [ArticleController::class, 'create'])->name('create_new_article');
Route::post('articles', [ArticleController::class, 'save'])->name('save_new_article');
Route::get('articles/{article}', [ArticleController::class, 'view'])->name('view_article');
Route::get('articles/{article}/edit', [ArticleController::class, 'edit'])->name('edit_article');
Route::post('articles/{article}/update', [ArticleController::class, 'update'])->name('update_article');
Route::get('articles/{article}/delete', [ArticleController::class, 'delete'])->name('delete_article');

Route::get('users/{user}', [UserController::class, 'show'])->name('show_user_profile');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
