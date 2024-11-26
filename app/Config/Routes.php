<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'AuthController::index');
$routes->get('register', 'AuthController::form_register');

// Web route untuk halaman produk
$routes->get('products/merchant', 'ProductController::index');
$routes->get('products/customer', 'ProductController::customer');
$routes->get('products/create', 'ProductController::create');
$routes->get('products/edit/(:num)', 'ProductController::edit/$1');

$routes->get('transactions/customer', 'TransactionController::index');
$routes->get('transactions/merchant', 'TransactionController::merchant');


// API routes
$routes->group('api', ['filter' => 'cors'], function ($routes) {
    // Public API (no JWT required)
    $routes->post('auth/login', 'AuthController::login');
    $routes->post('auth/register', 'AuthController::register');
    $routes->post('auth/logout', 'AuthController::logout');


    // Protected API routes (JWT required)
    $routes->group('', ['filter' => 'jwt'], function ($routes) {
        // API produk (JSON)
        $routes->group('products', function ($routes) {
            $routes->get('', 'ProductController::get_data_product'); // Untuk JSON produk
            $routes->post('', 'ProductController::store');
            $routes->put('(:num)', 'ProductController::update/$1');
            $routes->delete('(:num)', 'ProductController::delete/$1');
        });

        $routes->post('transactions', 'TransactionController::store');
    });
});

