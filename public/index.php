<?php

require_once(__DIR__ . "./../vendor/autoload.php");

use app\controller\AuthController;
use app\controllers\SiteController;
use core\Application;

$app = new Application(dirname(__DIR__));

/** SiteController */
$app->router->get('/', [SiteController::class, 'home']);
$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->post('/contact', [SiteController::class, 'handleContact']);

/** AuthController */
$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);


$app->run();