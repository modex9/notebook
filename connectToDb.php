<?php

class DatabaseInterface
{
    private $servername;
    private $username ;
    private $password;
    private $db;
    private $connection;
    private static $instance;

    public function __construct($db, $username = 'root', $password = '', $servername = 'localhost')
    {
        $this->db = $db;
        $this->username = $username;
        $this->password = $password;
        $this->servername = $servername;
    }

    public function connect()
    {
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->db);
        if ($conn->connect_error)
            die("Connection failed: " . $conn->connect_error);
        $this->connection =  $conn;
        return $this;
    }

    public function disconnect()
    {
        if(isset($this->connection))
            $this->connection->close();
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function runQuery($query)
    {
        if (isset($this->connection)) {
            $result = $this->connection->query($query);
            return $result;
        }
    }
}
