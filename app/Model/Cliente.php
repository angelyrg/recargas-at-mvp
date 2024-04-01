<?php

require_once 'Model.php';

class Cliente extends Model {

    public function __construct()
    {
        parent::__construct('clientes');
    }

    public function getByPlayerID($playerID){
        $stm = $this->db->prepare("SELECT * FROM {$this->table} WHERE playerID = :value");
        $stm->bindValue(":value", $playerID);        
        $stm->execute();
        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    public function addSaldo(int $client_id, float $montoRecargar) {
        $cliente = $this->getById($client_id);
        if (!$cliente) {
            return false; 
        }
    
        $nuevoSaldo = $cliente['saldo'] + $montoRecargar;

        $stm = $this->db->prepare("UPDATE {$this->table} SET saldo = :nuevoSaldo WHERE id = :client_id");
        $stm->bindValue(":nuevoSaldo", $nuevoSaldo);
        $stm->bindValue(":client_id", $client_id);
        
        return $stm->execute();
    }


    public function corregirSaldo(int $client_id, float $montoAnterior, float $montoCorrecto) {
        $cliente = $this->getById($client_id);
        if (!$cliente) {
            return false;
        }
    
        $nuevoSaldo = ($cliente['saldo'] - $montoAnterior) + $montoCorrecto;
    
        $stm = $this->db->prepare("UPDATE {$this->table} SET saldo = :nuevoSaldo WHERE id = :client_id");
        $stm->bindValue(":nuevoSaldo", $nuevoSaldo);
        $stm->bindValue(":client_id", $client_id);

        return $stm->execute();
    }
    

}