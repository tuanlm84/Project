<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Router;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

session_start();

$router = new Router();

// 🛠 Đăng ký route thủ công
$router->get('/', 'HomeController@index');

$router->get('/auth/login', 'AuthController@login');
$router->post('/auth/login', 'AuthController@login');
$router->get('/auth/google', 'AuthController@google');
$router->get('/auth/callback', 'AuthController@callback');
$router->get('/auth/logout', 'AuthController@logout');

$router->get('/upload', 'UploadController@index');
$router->post('/upload', 'UploadController@submit');

$router->get('/download', 'DownloadController@index');

$router->get('/status/{id}', 'StatusController@show');

// Tự động fallback nếu không match
$router->dispatch($_SERVER['REQUEST_URI']);
