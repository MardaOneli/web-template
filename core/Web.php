<?php

require_once 'Router.php';

$router = new Router();

// Definisikan route
$router->get('/', 'LoginController@index');
$router->post('/', 'LoginController@login');

$router->get('/logout', 'LoginController@logout');

$router->get('/register', 'RegisterController@index');
$router->post('/register', 'RegisterController@register');

$router->get('/dashboard', 'PageController@index');

$router->get('/admin', 'AdminController@index');

// Jalankan router
$router->run();
