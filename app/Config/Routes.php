<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/api/coasters', 'CoasterController::getAll');
$routes->get('/api/coasters/(:num)', 'CoasterController::get/$1');
$routes->post('/api/coasters', 'CoasterController::create');
$routes->delete('/api/coasters/(:num)', 'CoasterController::delete/$1');
$routes->put('/api/coasters/(:num)', 'CoasterController::update/$1');

$routes->get('/api/wagons', 'WagonController::getAll');
$routes->get('/api/wagons/(:num)', 'WagonController::get/$1');
$routes->post('/api/wagons', 'WagonController::create');
$routes->delete('/api/wagons/(:num)', 'WagonController::delete/$1');
$routes->put('/api/wagons/(:num)', 'WagonController::update/$1');
