<?php

require_once(__DIR__ . '/../Model/Cliente.php');
require_once(__DIR__ . '/Controller.php');

class ClienteController extends Controller {
    
    private $clienteModel;

    public function __construct(PDO $coneccion)
    {
        $this->clienteModel = new Cliente($coneccion);
    }

    public function search() {
        // Lee el cuerpo de la petición
        $inputJSON = file_get_contents('php://input');
        // Decodifica el JSON a un objeto PHP
        $input = json_decode($inputJSON);
    
        // Asume que el JSON enviado está estructurado como { "playerID": "valor" }
        $playerID = isset($input->playerID) ? trim($input->playerID) : '';

        $client = $this->clienteModel->getByPlayerID($playerID);    

        if (!$client) {
            http_response_code(404);
            echo json_encode(["error" => "Cliente no encontrado"]);
        } else {
            header('Content-Type: application/json');
            echo json_encode($client);
        }
    }

    public function show() {
        header('Content-Type: application/json');

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON);

        $client_id = isset($input->client_id) ? trim($input->client_id) : 0;

        $client = $this->clienteModel->getById($client_id);    

        if (!$client) {
            http_response_code(404);
            echo json_encode(["error" => "Cliente no encontrado"]);
        } else {
            echo json_encode($client);
        }
    }

}