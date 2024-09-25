<?php

namespace src;

class UrlsModel
{
    private \PDO $conn;
    private int $userId;
    public function __construct(DatabaseModel $database)
    {
        $this->conn = $database->getConnection();

    }

    public function getAllForUser($userId) : array
    {
        $sql = "SELECT * 
                FROM urls
                WHERE user_id = :userId 
                ORDER BY id_url";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":userId", $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getForUser($id,$userId) : array|bool
    {
        $sql = "SELECT *
                FROM urls
                WHERE id_url = :id 
                AND user_id = :userId";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, \PDO::PARAM_STR);
        $stmt->bindValue(":userId", $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createForUser($url,$userId) : string
    {
        $sql = "INSERT INTO urls (link_url,user_id)
                VALUES(:url, :userId)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":userId", $userId, \PDO::PARAM_INT);
        $stmt->bindValue(":url", $url, \PDO::PARAM_STR);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function deleteForUser($id,$userId) : int
    {
        $sql = "DELETE FROM urls
                WHERE id_url = :id
                AND user_id = :userId";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":userId", $userId, \PDO::PARAM_INT);
        $stmt->bindValue(":id", $id, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
