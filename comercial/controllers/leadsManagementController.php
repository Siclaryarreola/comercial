<?php
require_once('../models/leadsManagementModel.php');

class LeadsManagementController {
    private $model;

    public function __construct() {
        $this->model = new LeadsManagementModel();
    }

    // Obtener todos los registros de una tabla
    public function index($tableName) {
        if (empty($tableName)) {
            error_log("Tabla no especificada en index");
            return [];
        }

        return $this->model->getAll($tableName);
    }
    
    
    // Obtener datos para las listas desplegables
public function getDropdownData() {
    $contactos = $this->model->getAll('contactoleads');
    $estatus = $this->model->getAll('estatusleads');
    $gerentes = $this->model->getAll('gerentesleads');
    $periodos = $this->model->getAll('periodosleads');
    $negocios = $this->model->getAll('negocioleads');
    $leads = $this->model->getLeadsWithRelations(); // Traer leads con relaciones.
    $sucursales = $this->model->getAll('sucursales'); // Traer sucursales.

    return [
        'contactos' => $contactos,
        'estatus' => $estatus,
        'gerentes' => $gerentes,
        'periodos' => $periodos,
        'negocios' => $negocios,
        'leads' => $leads,
        'sucursales' => $sucursales, // Agregar sucursales al arreglo de retorno
        'contactosCount' => count($contactos),
        'estatusCount' => count($estatus),
        'gerentesCount' => count($gerentes),
        'periodosCount' => count($periodos),
        'negociosCount' => count($negocios),
        'sucursalesCount' => count($sucursales) // Contar sucursales
    ];
}



    // Agregar un nuevo registro a una tabla
    public function add($tableName, $data) {
        if (empty($tableName) || empty($data) || !is_array($data)) {
            error_log("Datos inválidos para agregar en {$tableName}");
            return ['success' => false, 'message' => 'Datos incompletos o tabla no especificada'];
        }

        $result = $this->model->add($tableName, $data);

        if ($result) {
            return ['success' => true, 'message' => 'Registro agregado exitosamente'];
        } else {
            return ['success' => false, 'message' => 'Error al agregar el registro'];
        }
    }


    // Actualizar un registro existente
    public function update($tableName, $data, $id) {
        if (empty($tableName) || empty($data) || !$id) {
            error_log("Datos inválidos para actualizar en {$tableName}");
            return ['success' => false, 'message' => 'Datos incompletos o tabla no especificada'];
        }

        $result = $this->model->update($tableName, $data, $id);

        if ($result) {
            return ['success' => true, 'message' => 'Registro actualizado exitosamente'];
        } else {
            return ['success' => false, 'message' => 'Error al actualizar el registro'];
        }
    }


    // Eliminar un registro existente
    public function delete($tableName, $id) {
        if (empty($tableName) || !$id) {
            error_log("Datos inválidos para eliminar en {$tableName}");
            return ['success' => false, 'message' => 'ID no proporcionado o tabla no especificada'];
        }

        $result = $this->model->delete($tableName, $id);

        if ($result) {
            return ['success' => true, 'message' => 'Registro eliminado exitosamente'];
        } else {
            return ['success' => false, 'message' => 'Error al eliminar el registro'];
        }
    }
}



// Manejo de acciones desde las solicitudes HTTP
if (isset($_GET['action'])) {
    $controller = new LeadsManagementController();
    $tableName = $_GET['table'] ?? null;

    switch ($_GET['action']) {
        case 'index':
            $data = $controller->index($tableName);
            echo json_encode(['success' => true, 'data' => $data]);
            break;

        case 'add':
            $data = json_decode(file_get_contents('php://input'), true);
            $response = $controller->add($tableName, $data);
            echo json_encode($response);
            break;

        case 'update':
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $_GET['id'] ?? null;
            $response = $controller->update($tableName, $data, $id);
            echo json_encode($response);
            break;

        case 'delete':
            $id = $_GET['id'] ?? null;
            $response = $controller->delete($tableName, $id);
            echo json_encode($response);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
            break;
    }
}
