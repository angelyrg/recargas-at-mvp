<?php

require_once __DIR__ . '/../Services/Database.php';

class Model {
    
    protected $table;
    protected $db;

    public function __construct($table) {
        $this->table = $table;
        // $this->db = Database::getInstance()->getConnection();

        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    public function getAll(){
        $stm = $this->db->prepare("SELECT * FROM {$this->table}");
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id){
        $stm = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stm->bindValue(":id", $id);
        $stm->execute();
        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data){
        $sql = "INSERT INTO {$this->table} (";
        foreach ($data as $key => $value) {
            $sql .= "{$key},";
        }
        $sql = rtrim($sql, ',');
        $sql .= ") VALUES (";

        foreach ($data as $key => $value) {
            $sql .= ":{$key},";
        }
        $sql = rtrim($sql, ',');
        $sql .= ")";

        $stm = $this->db->prepare($sql);

        foreach ($data as $key => $value) {
            $stm->bindValue(":{$key}", $value);
        }
        return $stm->execute();
    }


    public function updateById($id, $data){
        $sql = "UPDATE {$this->table} SET ";
        foreach ($data as $key => $value) {
            $sql .= "{$key} = :{$key},";
        }
        $sql = trim($sql, ',');
        $sql .= " WHERE id = :id ";

        $stm = $this->db->prepare($sql);
        foreach ($data as $key => $value) {
            $stm->bindValue(":{$key}", $value);
        }
        
        $stm->bindValue(":id", $id);
        return $stm->execute();
    }

    public function deleteById($id){
        $stm = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stm->bindValue(':id', $id);
        $stm->execute();
    }

    public function getTotalGroupByColumn($column){
        $sql = "SELECT {$column}, COUNT(*) AS total FROM {$this->table} GROUP BY {$column}";
        $stm = $this->db->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
