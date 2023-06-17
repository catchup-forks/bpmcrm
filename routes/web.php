<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Requests\RequestsController;

Route::group(['middleware' => ['auth', 'authorize']], function () {

// Routes related to Authentication (password reset, etc)
// Auth::routes();
    Route::namespace('Admin')->prefix('admin')->group(function () {
        Route::resource('about', 'AboutController');
        Route::resource('groups', 'GroupController')->only(['index', 'edit', 'show']);
        Route::resource('preferences', 'PreferenceController');
        Route::resource('users', 'UserController');
    });

    Route::namespace('Process')->prefix('processes')->group(function () {
        Route::resource('environment-variables', 'EnvironmentVariablesController');
        Route::resource('documents', 'DocumentController');
        Route::resource('screens', 'ScreenController');
        Route::resource('screen-builder', 'ScreenBuilderController')->parameters([
            'screen-builder' => 'screen'
        ])->only(['edit']);
        Route::resource('scripts', 'ScriptController');
        Route::resource('categories', 'ProcessCategoryController')->parameters([
            'categories' => 'processCategory'
        ]);
    });

    Route::resource('processes', 'ProcessController');
    Route::get('profile/edit', 'ProfileController@edit')->name('profile.edit');
    Route::get('profile/{id}', 'ProfileController@show');
    // Ensure our modeler loads at a distinct url
    Route::get('modeler/{process}', 'Process\ModelerController')->name('modeler');

    Route::get('/', 'HomeController@index')->name('home');

    Route::get('requests/{type}', 'RequestController@index')
        ->where('type', 'all|in_progress|completed')
        ->name('requests_by_type');
    Route::resource('requests', 'RequestController')->only([
        'index', 'show'
    ]);

    Route::resource('tasks', 'TaskController');
});

// Add our broadcasting routes
Broadcast::routes();

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('password/success', function () {
    return view('auth.passwords.success', ['title' => __('Password Reset')]);
})->name('password-success');
