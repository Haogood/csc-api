<?php

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

$router->post('auth/login', 'AuthController@authenticate');

$router->group(['prefix' => 'api', 'middleware' => ['jwt.auth']], function() use ($router) {
    $router->get('user', 'UserController@index');
    $router->post('mainpages', 'MainPageController@store');
    $router->post('mainpages/switch/{id}', 'MainPageController@switch');
    $router->put('mainpages/{id}', 'MainPageController@update');
    $router->delete('mainpages/{id}', 'MainPageController@destroy');
});

$router->group(['prefix' => 'api'], function () use ($router) {

    /* ---Main Page--- */ 
    $router->get('mainpages', 'MainPageController@index');
    $router->get('mainpages/{id}', 'MainPageController@show');
    $router->get('mainpages/src/{id}', 'MainPageController@getImage');
    
});

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/key', function () {
    return str_random(32);
});