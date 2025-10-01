<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// app/Config/Routes.php  (tambah ini)
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/auth/attempt', 'Auth::attempt');
$routes->get('/logout', 'Auth::logout');
$routes->get('/dashboard', 'Dashboard::index');

// Bahan Baku CRUD
$routes->group('bahan', function($routes) {
    $routes->get('/', 'Bahan::index');
    $routes->get('create', 'Bahan::create');
    $routes->post('store', 'Bahan::store');
});

