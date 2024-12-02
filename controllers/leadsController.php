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

    public function addLead($data) {
        return $this->model->addLead($data);
    }
}
?>
