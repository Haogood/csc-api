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

$router->group(['prefix' => 'api/frontend'], function () use ($router) {
    $router->get('mainpage', 'MainPageController@index');
    $router->get('mainpage/{id}', 'MainPageController@show');
    $router->post('mainpage', 'MainPageController@store');
    $router->put('mainpage/{id}', 'MainPageController@update');
});

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/key', function () {
    return str_random(32);
});