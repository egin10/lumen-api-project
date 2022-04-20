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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/login', ['as' => 'auth.login', 'uses' => 'AuthController@login']);
    $router->post('/logout', ['as' => 'auth.logout', 'uses' => 'AuthController@logout']);
    
    $router->group(['prefix' => 'mongo'], function() use ($router) {
        $router->get('/', ['as' => 'mongo.index', 'uses' => 'MongoController@index']);
        $router->get('/{slug}', ['as' => 'mongo.show', 'uses' => 'MongoController@show']);
        $router->post('/create', ['as' => 'mongo.create', 'uses' => 'MongoController@store']);
        $router->patch('/{slug}', ['as' => 'mongo.update', 'uses' => 'MongoController@update']);
        $router->delete('/{slug}', ['as' => 'mongo.delete', 'uses' => 'MongoController@delete']);
    });

    $router->group(['prefix' => 'firebase'], function() use ($router) {
        $router->get('/', ['as' => 'firebase.index', 'uses' => 'FirebaseController@index']);
        $router->get('/{slug}', ['as' => 'firebase.show', 'uses' => 'FirebaseController@show']);
        $router->post('/create', ['as' => 'firebase.create', 'uses' => 'FirebaseController@store']);
        $router->patch('/{slug}', ['as' => 'firebase.update', 'uses' => 'FirebaseController@update']);
        $router->delete('/{slug}', ['as' => 'firebase.delete', 'uses' => 'FirebaseController@delete']);
    });

    $router->get('filter-data', ['as' => 'filter-data', 'uses' => 'FilterDataController@filterData']);
});
