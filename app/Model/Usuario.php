<?php

require_once 'Model.php';

class Usuario extends Model {

    public function __construct(PDO $connecion)
    {
        parent::__construct('usuarios', $connecion);
    }

}