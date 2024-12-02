<?php
require_once('../models/leadsModel.php');

class LeadsController {
    private $model;

    public function __construct() {
        $this->model = new LeadModel();
    }

    public function index($filters = []) {
        return $this->model->getLeads($filters);
    }

    public function addLead() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'contacto' => $_POST['contacto'] ?? '',
                'correo' => $_POST['correo'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'empresa' => $_POST['empresa'] ?? '',
                'giro' => $_POST['giro'] ?? '',
                'localidad' => $_POST['localidad'] ?? '',
                'sucursal' => $_POST['sucursal'] ?? '',
                'medio_contacto' => $_POST['medio_contacto'] ?? '',
                'estatus' => $_POST['estatus'] ?? '',
                'linea_negocio' => $_POST['linea_negocio'] ?? '',
                'periodo' => $_POST['periodo'] ?? '',
                'gerente_responsable' => $_POST['gerente'] ?? '',
                'id_usuario' => $_SESSION['user']['id_usuario'] ?? 1,
                'archivo' => null,
                'cotizacion' => null,
                'notas' => null,
            ];

            $result = $this->model->addLead($data);

            if ($result) {
                header("Location: ../views/leads.php?success=1");
            } else {
                header("Location: ../views/leads.php?error=1");
            }
        }
    }

    public function getDropdownData() {
        // Este m¨¦todo obtiene los datos necesarios para los desplegables en la vista
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

// Manejo de acciones
if (isset($_GET['action']) && $_GET['action'] === 'addLead') {
    $controller = new LeadsController();
    $controller->addLead();
}
?>
