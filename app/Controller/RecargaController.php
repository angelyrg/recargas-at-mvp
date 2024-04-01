<?php

require_once(__DIR__ . '/Controller.php');
require_once(__DIR__ . '/../Model/Recarga.php');
require_once(__DIR__ . '/../Model/Cliente.php');
require_once(__DIR__ . '/../Model/CorrecionRecarga.php');
require_once(__DIR__ . '/../Traits/UploadFile.php');

class RecargaController extends Controller {
    use FileUpload;
    
    private $recargaModel;
    private $clienteModel;
    private $correccionRecargaModel;

    public function __construct() {
        $this->recargaModel = new Recarga();
        $this->clienteModel = new Cliente();
        $this->correccionRecargaModel = new CorrecionRecarga();
    }

    public function store(){
        header('Content-Type: application/json');

        $client_id = isset($_POST['client_id']) ? $_POST['client_id'] : null;
        $montoRecargar = isset($_POST['montoRecargar']) ? $_POST['montoRecargar'] : null;
        $banco = isset($_POST['banco']) ? $_POST['banco'] : null;
        $canal_comunicacion = isset($_POST['canal_comunicacion']) ? $_POST['canal_comunicacion'] : null;


        if (!$client_id || !$montoRecargar || !$banco || !$canal_comunicacion) {
            echo json_encode(["success" => false, "message" => "Faltan datos obligatorios."]);
            return;
        }


        if (!isset($_FILES['voucherImg'])) {
            echo json_encode(["success" => false, "message" => "Archivo de comprobante no proporcionado."]);
            return;
        }


        $uploadDirectory = __DIR__.'/../../public/uploads/';
        $fileName = $this->uploadFile($_FILES['voucherImg'], $uploadDirectory);

        if (!$fileName) {
            echo json_encode(["success" => false, "message" => "Error al subir el archivo."]);
            return;
        }


        // Inicia una transacción
        // $this->recargaModel->db->beginTransaction();

        try {
            // Insertar los datos de recarga.
            $data = [
                "cliente_id" => $client_id,
                "monto" => (float)$montoRecargar,
                "banco" => $banco,
                "canal_comunicacion" => $canal_comunicacion,
                "voucher_image" => $fileName
            ];

            $result = $this->recargaModel->insert($data);

            if (!$result) {
                throw new Exception("Error al insertar los datos de recarga.");
            }

            // Actualizar el saldo del cliente.
            $actualizado = $this->clienteModel->addSaldo($client_id, $montoRecargar);

            if (!$actualizado) {
                throw new Exception("Error al agregar el saldo del cliente.");
            }

            // Confirmar la transacción
            // $this->recargaModel->db->commit();
            echo json_encode([
                "success" => true,
                "message" => "Recarga realizada y saldo actualizado correctamente.",
                "resume" => $data
            ]);

        } catch (Exception $e) {
            // Revertir la transacción si algo sale mal
            // $this->recargaModel->db->rollBack();
            echo json_encode(["success" => false, "message" => $e->getMessage()]);
        }
        

    }

    public function historial(){
        header('Content-Type: application/json');

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON);
        $client_id = isset($input->client_id) ? trim($input->client_id) : null;

        if (!$client_id) {
            echo json_encode(["success" => false, "message" => "Falta ID del cliente."]);
            return;
        }

        $result = $this->recargaModel->getByClientId($client_id);

        if (!$result) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "No se pudo obtener el historial"
            ]);
        }else{
            echo json_encode([
                "success" => true,
                "message" => "Historial obtenido",
                "data" => $result
            ]);
        }
    }

    public function show(){
        header('Content-Type: application/json');

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON);
        $recarga_id = isset($input->recarga_id) ? trim($input->recarga_id) : 0;

        if (!$recarga_id) {
            echo json_encode(["success" => false, "message" => "Falta ID de la recarga."]);
            return;
        }

        $result = $this->recargaModel->getById($recarga_id);

        if (!$result) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "No se pudo obtener la recarga."
            ]);
        }else{
            echo json_encode([
                "success" => true,
                "message" => "Recarga obtenido.",
                "data" => $result
            ]);
        }

    }

    public function update(){
        header('Content-Type: application/json');

        $recarga_id = isset($_POST['recarga_id_edit']) ? $_POST['recarga_id_edit'] : null;
        $client_id = isset($_POST['client_id_edit']) ? $_POST['client_id_edit'] : null;
        $montoAnterior = isset($_POST['montoAnterior_edit']) ? $_POST['montoAnterior_edit'] : null;
        $montoRecargar_edit = isset($_POST['montoRecargar_edit']) ? $_POST['montoRecargar_edit'] : null;
        $banco_edit = isset($_POST['banco_edit']) ? $_POST['banco_edit'] : null;
        $canal_comunicacion_edit = isset($_POST['canal_comunicacion_edit']) ? $_POST['canal_comunicacion_edit'] : null;


        if (!$recarga_id || !$client_id || !$montoAnterior || !$montoRecargar_edit || !$banco_edit || !$canal_comunicacion_edit) {
            echo json_encode(["success" => false, "message" => "Faltan datos obligatorios para actualizar Recarga."]);
            return;
        }

        // Inicia una transacción
        // $this->recargaModel->db->beginTransaction();

        try {
            $data = [
                "cliente_id" => $client_id,
                "monto" => (float)$montoRecargar_edit,
                "banco" => $banco_edit,
                "canal_comunicacion" => $canal_comunicacion_edit
            ];

            $result = $this->recargaModel->updateById($recarga_id, $data);

            if (!$result) {
                throw new Exception("Error al actualizar los datos de recarga.");
            }

            // Actualizar el saldo del cliente.
            $actualizado = $this->clienteModel->corregirSaldo($client_id, $montoAnterior, $montoRecargar_edit);

            if (!$actualizado) {
                throw new Exception("Error al actualizar el saldo del cliente.");
            }

            // Guardar el historial de correcciones.
            // recarga_id	monto_anterior	monto_nuevo	motivo
            $historialData = [
                "recarga_id" => $recarga_id,
                "monto_anterior" => (float)$montoAnterior,
                "monto_nuevo" => (float)$montoRecargar_edit,
            ];
            $guardarHistorial = $this->correccionRecargaModel->insert($historialData);

            // Confirmar la transacción
            // $this->recargaModel->db->commit();
            echo json_encode([
                "success" => true,
                "message" => "Recarga actualizado y saldo actualizado correctamente.",
                "resume" => $data
            ]);

        } catch (Exception $e) {
            // Revertir la transacción si algo sale mal
            // $this->recargaModel->db->rollBack();
            echo json_encode(["success" => false, "message" => $e->getMessage()]);
        }
    }

    public function grafico(){
        header('Content-Type: application/json');

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON);
        $column_name = isset($input->column) ? trim($input->column) : null;

        if (!$column_name) {
            echo json_encode(["success" => false, "message" => "Falta el nombre de la columna."]);
            return;
        }

        $result = $this->recargaModel->getTotalGroupByColumn($column_name);

        if (!$result) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "No se pudo obtener el historial"
            ]);
        } else {


            $datosParaGrafico = array_map(function ($row) use ($column_name) {
                return [
                    'name' => $row[$column_name],
                    'y' => (int) $row['total'],
                ];
            }, $result);

            echo json_encode([
                "success" => true,
                "message" => "Datos obtenidos",
                "data_to_graph" => $datosParaGrafico
            ]);
        }
    }

    public function reporte(){
        $this->render('recargas/reporte', [], 'base');
    }

}