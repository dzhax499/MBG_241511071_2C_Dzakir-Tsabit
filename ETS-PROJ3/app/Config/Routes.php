<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// app/Config/Routes.php
// $routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/auth/attempt', 'Auth::attempt');
$routes->get('/logout', 'Auth::logout');
$routes->get('/dashboard', 'Dashboard::index');

$routes->group('bahan', ['filter' => 'role:gudang'], function ($routes) {
    $routes->get('/', 'Bahan::index');
    $routes->get('create', 'Bahan::create');
    $routes->post('store', 'Bahan::store');
    $routes->get('edit/(:num)', 'Bahan::edit/$1');
    $routes->post('update/(:num)', 'Bahan::update/$1');
    // $routes->get('delete/(:num)', 'Bahan::delete/$1');
    $routes->get('delete/(:num)', 'Bahan::delete/$1');
    $routes->post('destroy/(:num)', 'Bahan::destroy/$1');
});

$routes->group('permintaan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Permintaan::index');
    $routes->get('create', 'Permintaan::create', ['filter' => 'role:dapur']);
    $routes->post('store', 'Permintaan::store', ['filter' => 'role:dapur']);
    $routes->get('approve/(:num)', 'Permintaan::approve/$1', ['filter' => 'role:gudang']);
    $routes->get('reject_form/(:num)', 'Permintaan::reject_form/$1', ['filter' => 'role:gudang']);
    $routes->post('reject/(:num)', 'Permintaan::reject/$1', ['filter' => 'role:gudang']);
    $routes->get('reject_form/(:num)', 'Permintaan::reject_form/$1', ['filter' => 'role:gudang']);
    $routes->get('detail/(:num)', 'Permintaan::detail/$1');
});

// jika sudah login maka login akan di arahkan ke dashboard
$routes->get('/', function () {
    $session = session();
    if ($session->get('isLoggedIn')) {
        return redirect()->to('/dashboard');
    }
    return redirect()->to('/login');
});