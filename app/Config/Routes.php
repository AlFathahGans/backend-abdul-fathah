<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// AUTH 

$routes->group('api', function ($routes) {
    // Public routes (no JWT required)
    $routes->post('auth/login', 'AuthController::login');
    $routes->post('auth/register', 'AuthController::register');

    // Protected routes
    $routes->group('', ['filter' => 'jwt'], function ($routes) {
        $routes->group('products', function ($routes) {
            $routes->get('', 'ProductController::index');
            $routes->post('', 'ProductController::create');
            $routes->put('(:num)', 'ProductController::update/$1');
            $routes->delete('(:num)', 'ProductController::delete/$1');
        });

        $routes->post('transactions', 'TransactionController::create');
    });
});

