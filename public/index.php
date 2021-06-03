<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\SiteController;
use App\Models\User;
use Kareem\Dominoes\Application;


$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
  'user' => User::class,
  'db' => [
    'dsn' => $_ENV['DB_DSN'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD']
  ]
];

$app = new Application(dirname(__DIR__), $config);

$app->route->get('/', [SiteController::class, 'welcome']);

// Using middlewares in this routes
$app->route->get('/dashboard', [SiteController::class, 'dashboard']);
$app->route->get('/profile', [SiteController::class, 'profile']);

$app->route->get('/login', [AuthController::class, 'login']);
$app->route->post('/login', [AuthController::class, 'login']);
$app->route->get('/register', [AuthController::class, 'register']);
$app->route->post('/register', [AuthController::class, 'register']);
$app->route->get('/logout', [AuthController::class, 'logout']);

// Fire the app
$app->run();
