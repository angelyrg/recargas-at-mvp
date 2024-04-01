<?php

require_once 'Model.php';

class Recarga extends Model {

    public function __construct()
    {
        parent::__construct('recargas');
    }

    public function getByClientId($clientId){
        $stm = $this->db->prepare("SELECT * FROM {$this->table} WHERE cliente_id = :cliente_id ORDER BY id DESC");
        $stm->bindValue(":cliente_id", $clientId);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

}