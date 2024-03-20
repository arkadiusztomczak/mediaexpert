<?php

class Database {
    private $host = 'localhost';
    private $username = 'mediaexpert';
    private $password = 'C]OT_N0ubQb|aqD';
    private $database = 'mediaexpert';
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}