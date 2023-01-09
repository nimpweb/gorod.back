<?php

use app\controllers\AuthController;
use app\controllers\SiteController;
use app\controllers\UserController;
use core\Application;

require_once(__DIR__ . "./../vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
    'userClass' => app\models\User::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ],
    'soap' => [
        'host' => $_ENV['SOAP_HOST'],
        'terminal' => $_ENV['SOAP_TERMINAL'],
    ]
];
$app = new Application(dirname(__DIR__), $config );

/** SiteController */
$app->router->get('/', [SiteController::class, 'home']);
$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->post('/contact', [SiteController::class, 'handleContact']);

/** AuthController */
$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/logout', [AuthController::class, 'logout']);

$app->router->get('/getAllUsers', [UserController::class, 'getAllUsers']);

$app->router->get('/userInfo', [UserController::class, 'userInfo']);



$app->run();