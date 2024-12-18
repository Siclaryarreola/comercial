<?php
require_once('../models/leadsModel.php');

class LeadsController {
    private $model;

    public function __construct() {
        $this->model = new LeadModel();
    }

/* ------------------------------------------------------------------------------------------*/
    // Obtener leads con filtros opcionales
    public function index($filters = []) {
        return $this->model->getLeads($filters);
    }
    
    public function filterLeads() {
    $filters = [
        'generador' => $_POST['generador'] ?? null,
        'sucursal' => $_POST['sucursal'] ?? null,
        'fecha' => $_POST['fecha'] ?? null,
        'negocio' => $_POST['negocio'] ?? null
    ];
    $leads = $this->model->getLeads($filters);
    $etapasLeads = $this->model->getEtapasLeads($filters); // Asegúrate de implementar esto en el modelo
    echo json_encode(['leads' => $leads, 'etapasLeads' => $etapasLeads]);
}

/* ------------------------------------------------------------------------------------------*/
   // Agregar un nuevo Lead
public function addLead() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'contacto' => trim($_POST['contacto'] ?? ''),
            'correo' => trim($_POST['correo'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'giro' => trim($_POST['giro'] ?? ''),
            'localidad' => trim($_POST['localidad'] ?? ''),
            'empresa' => trim($_POST['empresa'] ?? ''),
            'sucursal' => intval($_POST['sucursal'] ?? 0),
            'gerente_responsable' => intval($_POST['gerente_responsable'] ?? 0),
            'medio_contacto' => intval($_POST['medio_contacto'] ?? 0),
            'linea_negocio' => intval($_POST['linea_negocio'] ?? 0),
            'periodo' => intval($_POST['periodo'] ?? 0),
            'notas' => trim($_POST['notas'] ?? ''),
            'id_usuario' => $_SESSION['user']['id_usuario'] ?? 1,
            'estatus' => 1, // Por defecto, define un estatus inicial
            ];

        $result = $this->model->addLead($data);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Lead agregado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo agregar el lead']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    }
    exit;
}
/* ------------------------------------------------------------------------------------------*/
//Actualizar un lead
public function updateLead() {
    $id = $_GET['id'] ?? null;
    $data = json_decode(file_get_contents('php://input'), true); // Obtener datos JSON enviados

    if (!$id || empty($data)) {
        echo json_encode(['success' => false, 'message' => 'ID o datos inválidos']);
        return;
    }

    // Llamar al modelo para actualizar los datos
    $updated = $this->model->updateLead($id, $data);

    if ($updated) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar los datos']);
    }
}

/* ------------------------------------------------------------------------------------------*/
public function getHistory() {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
        return;
    }

    $history = $this->model->getHistory($id);
    echo json_encode(['success' => true, 'history' => $history]);
}


/* ------------------------------------------------------------------------------------------*/
public function getBranchData() {
    $branchData = $this->model->getBranchData(); // Llama al modelo para obtener los datos

    // Asegurarse de que cada sucursal tenga un conteo, incluso si está vacío
    $branchDataFormatted = [];
    foreach ($branchData as $branch) {
        $branchDataFormatted[] = [
            'sucursal' => $branch['sucursal'],
            'conteo' => $branch['conteo'] ?? 0 // Asignar 0 si 'conteo' no está definido
        ];
    }

    return $branchDataFormatted;
}


/* ------------------------------------------------------------------------------------------*/
    // Editar Lead existente
   public function editLead() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Leer datos JSON enviados desde fetch
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID del lead no proporcionado']);
            exit;
        }
        
        // Filtrar solo los campos editables
        $editableFields = ['contacto', 'correo', 'telefono', 'estatus', 'Comentarios'];
        $updatedFields = array_filter(
            $data,
            function ($key) use ($editableFields) {
                return in_array($key, $editableFields);
            },
            ARRAY_FILTER_USE_KEY
        );
        
        if (empty($updatedFields)) {
            echo json_encode(['success' => false, 'message' => 'No se proporcionaron campos válidos para actualizar']);
            exit;
        }



        // Actualizar en el modelo
        $result = $this->model->updateLead($id, $updatedFields);

        echo json_encode(['success' => $result, 'message' => $result ? 'Lead actualizado' : 'Error al actualizar el lead']);
    }
}

/* ------------------------------------------------------------------------------------------*/

    // Obtener Etapas de Leads
    public function getEtapasLeads() {
        return $this->model->getEtapasLeads();
    }
    
    public function getChartData() {
    $filters = json_decode(file_get_contents('php://input'), true); // Obtener filtros

    // Llama al modelo para obtener los datos filtrados
    $data = $this->model->getFilteredChartData($filters);

    // Procesar los datos en etiquetas y valores
    $labels = array_column($data, 'label'); // Extraer etiquetas
    $values = array_column($data, 'value'); // Extraer valores

    echo json_encode(['labels' => $labels, 'values' => $values]);
}

/* ------------------------------------------------------------------------------------------*/
    // Completar Lead
    public function completeLead() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                header("Location: ../views/leads.php?error=3");
                exit;
            }

            $cotizacion = trim($_POST['cotizacion'] ?? null);
            $archivo = $_FILES['archivo']['name'] ?? null;

            if ($archivo) {
                $uploadDir = '../Leads/';
                $filePath = $uploadDir . basename($archivo);
                move_uploaded_file($_FILES['archivo']['tmp_name'], $filePath);
            }

            $data = [
                'id' => $id,
                'cotizacion' => $cotizacion,
                'archivo' => $archivo
            ];

            $result = $this->model->completeLead($data);

            header("Location: ../views/leads.php?" . ($result ? "success=2" : "error=2"));
        }
    }
    
/* ------------------------------------------------------------------------------------------*/

    // Obtener datos para desplegables
    public function getDropdownData() {
        return [
            'contactos' => $this->model->getContactos(),
            'periodos' => $this->model->getPeriodos(),
            'estatus' => $this->model->getEstatus(),
            'gerentes' => $this->model->getGerentes(),
            'sucursales' => $this->model->getSucursales(),
            'negocios' => $this->model->getNegocios()
        ];
    }
}


/* ------------------------------------------------------------------------------------------*/
// Manejo de acciones
if (isset($_GET['action'])) {
    $controller = new LeadsController();

    switch ($_GET['action']) {
        case 'addLead':
            $controller->addLead();
            break;
        case 'editLead':
            $controller->editLead();
            break;
        case 'completeLead':
            $controller->completeLead();
            break;
        default:
            header("Location: ../views/leads.php?error=404");
            break;
    }
    
/* ------------------------------------------------------------------------------------------*/
    
}
