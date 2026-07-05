<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'EmployeeController::index');

$routes->get('employees', 'EmployeeController::list');
$routes->post('employees', 'EmployeeController::create');
$routes->put('employees/(:num)', 'EmployeeController::update/$1');
$routes->post('employees/(:num)', 'EmployeeController::update/$1'); // fallback for method-spoofing
$routes->delete('employees/(:num)', 'EmployeeController::delete/$1');
