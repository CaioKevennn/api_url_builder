<?php

namespace src;

class UserModel
{
    private \PDO $conn;

    public function __construct(DatabaseModel $databaseModel)
    {
        $this->conn = $databaseModel->getConnection();
    }

    public function getByApiKey (string $apiKey)
    {
        $sql = "SELECT * FROM  user_urls
                WHERE api_key = :apiKey";
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":apiKey",$apiKey,\PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}