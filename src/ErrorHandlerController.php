<?php

namespace src;

class ErrorHandlerController
{

    public static function handleException(\Throwable $exception) : void
    {
        http_response_code(500);

        echo json_encode([
            "code" => $exception->getCode(),
            "message" => $exception->getMessage(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine()
        ]);
    }

    public static function handleError(
        int $erroNumber,
        string $errorMessage,
        string $erroFile,
        string $errorLine
    ) : void {
        throw new \ErrorException($errorMessage, 0, $erroNumber, $erroFile, $errorLine);
    }

}
