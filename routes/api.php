<?php

use Src\Controllers\UserController;
use Src\Services\Router;

$router = new Router();

//$router->get('/', 'UserController@index');
$router->get('/users', 'UserController@index');
$router->post('/users', 'UserController@store');
$router->get('/users/{id}', 'UserController@show');
$router->put('/users/{id}', 'UserController@update');
$router->delete('/users/{id}', 'UserController@delete');


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$router->resolve($uri, $method);
