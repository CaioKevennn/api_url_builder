<?php

namespace src;
class UrlsController
{
    public function __construct(private UrlsModel $gateway, private int $userId)
    {
    }

    public function processRequest(string $method, ?string $id) : void
    {

        if ($id === null)
        {
            if ($method == "GET")
            {
                echo json_encode($this->gateway->getAllForUser($this->userId));
            }
            elseif ($method == "POST")
            {
                $data = (array) json_decode(file_get_contents("php://input", true));
                $errors = $this->getValidationErrors($data);

                if (empty($errors))
                {
                    $url = $this->createUrl($data);
                    $idCreatedUrl = $this->gateway->createForUser($url,$this->userId);

                    $this->responseCreated($idCreatedUrl);
                }
                else
                {
                    $this->respondUnprocessebleEntity($errors);
                }
            }
            else
            {
                $this->respondMethodNotAllowed("GET, POST");
            }
        }
        else
        {
            $url = $this->gateway->getForUser($id,$this->userId);
            if (! $url)
            {
                $this->respondNotFound($id);
                return;
            }

            switch ( $method )
            {
                case "GET":
                    echo json_encode($url);
                    break;
                case "DELETE":
                    $rows = $this->gateway->deleteForUser($id,$this->userId);
                    echo json_encode(["Message" => "Url deleted", "row: " => $rows]);
                    break;
                default:
                    $this->respondMethodNotAllowed("GET and  DELETE");
                    break;
            }
        }
    }

    private function createUrl(array $data) : string
    {
        $url = filter_var($data['url'], FILTER_SANITIZE_URL);
        unset($data['url']);
        $url .= "?";

        foreach ( $data as $index => $param )
        {
            $url .= $index . "=" . filter_var($param, FILTER_SANITIZE_FULL_SPECIAL_CHARS) . "&";
        }
        return rtrim($url, "&");
    }

    private function respondMethodNotAllowed(string $allowedmethods) : void
    {
        http_response_code(405);
        header("Allow: $allowedmethods");
    }

    private function respondNotFound(string $id) : void
    {
        http_response_code(404);
        echo json_encode(["Message" => "Url with id $id not found"]);
    }
    private function responseCreated(string $id) : void
    {
        http_response_code(201);
        echo json_encode(["Message" => "Url Created. Id: $id"]);
    }

    private function respondUnprocessebleEntity($errors)
    {
        http_response_code(422);
        echo json_encode(["Message" => $errors]);
    }

    private function getValidationErrors($data) : array
    {
        $errors = [];

        foreach ( $data as $index => $value )
        {
            if (preg_match('/[^a-zA-Z0-9 :?_&.\/-]/', $value))
            {
                $errors[] = "Value for '{$index}' contains invalid characters";
            }
        }

        return $errors;
    }


}
