<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('import/all', [
    'uses' => 'ImportController@all',
]);

$router->get('import/organizations', [
    'uses' => 'ImportController@organizations',
]);

$router->get('import/orders', [
    'uses' => 'ImportController@orders',
]);

$router->get('import/products', [
    'uses' => 'ImportController@products',
]);
