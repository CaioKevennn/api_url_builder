<?php

namespace src;

class UrlsModel
{
    private \PDO $conn;
    public function __construct(DatabaseModel $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll() : array
    {
        $sql = "SELECT * 
               FROM urls ";
        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function get($id) : array|bool
    {
        $sql = "SELECT *
                FROM urls
                WHERE id_url = :id ";
        $stm = $this->conn->prepare($sql);

        $stm->bindValue(":id", $id, \PDO::PARAM_STR);
        $stm->execute();
        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create($url) : string
    {
        $sql = "INSERT INTO urls (link_url)
                VALUES(:url)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue("url", $url, \PDO::PARAM_STR);
        $stmt->execute();
        return $this->conn->lastInsertId();

    }

    public function delete($id) : int
    {
        $sql = "DELETE FROM urls
                WHERE id_url = :id";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
