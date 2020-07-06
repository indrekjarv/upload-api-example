<?php

namespace Src\Gateways;

class MediaGateway
{

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = "SELECT id, media_name, media_storage_name, media_type, media_size FROM media;";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($id)
    {
        $statement = "SELECT id, media_name, media_storage_name, media_type, media_size FROM media WHERE id = ?;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(array $input)
    {
        $statement = "
            INSERT INTO media 
                (account_id, media_name, media_storage_name, media_type, media_size)
            VALUES
                (:account_id, :media_name, :media_storage_name, :media_type, :media_size);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(
                array(
                    'account_id' => $input['account_id'],
                    'media_name' => $input['media_name'],
                    'media_storage_name' => $input['media_storage_name'],
                    'media_type' => $input['media_type'] ?? null,
                    'media_size' => $input['media_size'] ?? null,
                )
            );
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $statement = "DELETE FROM media WHERE id = :id;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}