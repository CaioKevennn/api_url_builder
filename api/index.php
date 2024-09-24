<?php
declare(strict_types=1);
require dirname(__DIR__) . "/vendor/autoload.php";

use core\Router;
use src\DatabaseModel;
use src\UrlsController;
use src\ErrorHandlerController;
use src\UrlsModel;

set_exception_handler([ErrorHandlerController::class, "handleException"]);
set_error_handler([ErrorHandlerController::class, "handleError"]);

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
$route = new Router;


if ($route->getResource() !== "urls")
{
    http_response_code(404);
    exit;
}

header("Content-type: application/json; charset=UTF-8");

$database = new DatabaseModel($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASS"]);
$database->getConnection();
$urlsGateway = new UrlsModel($database);
$controller = new UrlsController($urlsGateway);
$controller->processRequest($_SERVER['REQUEST_METHOD'], $route->getId());