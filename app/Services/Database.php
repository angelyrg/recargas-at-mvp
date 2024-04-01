<?php

require_once __DIR__ . '/../../config/config.php';

class Database {
    private static $instance = null;
    private $connection;

    public function __construct() {
        $this->connect();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect() {
        require_once __DIR__ . '/../../config/database.php';
        
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
            
            $this->connection = new PDO($dsn, DB_USER, DB_PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        } catch (PDOException $e) {
            die("Coneccióni fallida: " . $e->getMessage());
        }
    }

    public function getConnection(){
        return $this->connection;
    }

    public function closeConnection(){
        $this->connection = null;
    }

}
