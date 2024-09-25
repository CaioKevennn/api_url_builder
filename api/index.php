<?php
declare(strict_types=1);

require __DIR__ . "/bootstrap.php";

use src\UserModel;
use core\Router;
use src\DatabaseModel;
use src\UrlsController;
use src\UrlsModel;
use src\Auth;

$route = new Router;

if ($route->getResource() !== "urls")
{
    http_response_code(404);
    exit;
}

$database = new DatabaseModel($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASS"]);
$database->getConnection();

$userGetway = new UserModel($database);
$auth = new Auth($userGetway);

if (! $auth->authenticateAPIKey())
{
    exit;
}

$userId = $auth->getUser_id();
$urlsGateway = new UrlsModel($database);

$controller = new UrlsController($urlsGateway,$userId);
$controller->processRequest($_SERVER['REQUEST_METHOD'], $route->getId());