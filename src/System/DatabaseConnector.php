<?php

namespace Src\System;

class DatabaseConnector
{

    private $dbConnection = null;

    public function __construct()
    {
        $db = getenv('DB_DATABASE');

        if (!file_exists($db)) {
            touch($db);
        }

        try {
            $this->dbConnection = new \PDO("sqlite:" . $db);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->dbConnection;
    }
}