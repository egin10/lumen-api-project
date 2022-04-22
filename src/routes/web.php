<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\MongoController;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('login', ['as' => 'auth.login', 'uses' => 'AuthController@login']);
    $router->post('register', ['as' => 'auth.register', 'uses' => 'AuthController@register']);
    $router->get('user', ['as' => 'auth.user', 'uses' => 'AuthController@user']);
    $router->post('logout', ['as' => 'auth.logout', 'uses' => 'AuthController@logout']);
    
    $router->group(['prefix' => 'mongo-toys'], function() use ($router) {
        $router->get('/', ['as' => 'mongo-toys.index', 'uses' => 'MongoController@index']);
        $router->get('{slug}', ['as' => 'mongo-toys.show', 'uses' => 'MongoController@show']);
        $router->post('/', ['as' => 'mongo-toys.create', 'uses' => 'MongoController@store']);
        $router->patch('{slug}', ['as' => 'mongo-toys.update', 'uses' => 'MongoController@update']);
        $router->delete('{slug}', ['as' => 'mongo-toys.delete', 'uses' => 'MongoController@delete']);
    });

    $router->group(['prefix' => 'firebase-books'], function() use ($router) {
        $router->get('/', ['as' => 'firebase-books.index', 'uses' => 'FirebaseController@index']);
        $router->get('{slug}', ['as' => 'firebase-books.show', 'uses' => 'FirebaseController@show']);
        $router->post('/', ['as' => 'firebase-books.store', 'uses' => 'FirebaseController@store']);
        $router->patch('{slug}', ['as' => 'firebase-books.update', 'uses' => 'FirebaseController@update']);
        $router->delete('{slug}', ['as' => 'firebase-books.delete', 'uses' => 'FirebaseController@delete']);
    });

    // Reqres.in
    $router->post('reqres/register', ['as' => 'reqres.register', 'uses' => 'ReqresController@register']);
    $router->post('reqres/login', ['as' => 'reqres.login', 'uses' => 'ReqresController@login']);

    $router->get('filter-data', ['as' => 'filter-data', 'uses' => 'FilterDataController@filterData']);
});
