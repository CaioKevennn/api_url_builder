<?php

namespace src;

class Auth
{
    private $userId;

    public function __construct(private UserModel $userModel )
    {
        
    }

    public function authenticateAPIKey() : bool
    {
        if (empty($_SERVER['HTTP_X_API_KEY']))
        {
            http_response_code(400);
            echo json_encode(["Message" => "API KEY is necessery"]);
            return false;
        }

        $api_key = $_SERVER['HTTP_X_API_KEY'];
        $user = $this->userModel->getByApiKey($api_key);

        if ($user === false)
        {
            http_response_code(401);
            echo json_encode(["Message" => "API KEY invalid"]);
            return false;
        }

        $this->userId = $user["id"];
        return true;
    }

    public function getUser_id() : int
    {
        return $this->userId;
    }


}
