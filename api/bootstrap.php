<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use src\ErrorHandlerController;

set_exception_handler([ErrorHandlerController::class, "handleException"]);
set_error_handler([ErrorHandlerController::class, "handleError"]);

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

header("Content-type: application/json; charset=UTF-8");
