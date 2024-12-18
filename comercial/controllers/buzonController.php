<?php
require_once('../models/buzonModel.php');

class BuzonController 
{
    private $model;

    public function __construct() {
        $this->model = new BuzonModel();
    }

    /**
     * Obtener todos los leads pendientes, con filtros opcionales.
     */
    public function getPendingLeads($clienteFiltro = '', $estatusFiltro = '') {
        return $this->model->getPendingLeadsFromDB($clienteFiltro, $estatusFiltro);
    }

    /**
     * Obtener los leads asignados a un gerente específico, con filtros opcionales.
     */
    public function getLeadsForGerente($gerenteId, $clienteFiltro = '', $estatusFiltro = '') {
        if (!$gerenteId) {
            throw new Exception("ID de gerente no proporcionado.");
        }
        return $this->model->getLeadsForGerenteFromDB($gerenteId, $clienteFiltro, $estatusFiltro);
    }

    /**
     * Asignar un lead a un gerente específico.
     */
    public function assignToGerente($leadId, $gerenteId) {
        if (!$leadId || !$gerenteId) {
            throw new Exception("ID de lead o gerente no proporcionado.");
        }
        return $this->model->assignLeadToGerente($leadId, $gerenteId);
    }

    /**
     * Obtener periodos para la lista desplegable.
     */
    public function getPeriodos() {
        return $this->model->getPeriodos();
    }

    /**
     * Obtener sucursales para la lista desplegable.
     */
    public function getSucursales() {
        return $this->model->getSucursales();
    }

    /**
     * Obtener gerentes para la lista desplegable.
     */
    public function getGerentes() {
        return $this->model->getGerentes();
    }

    /**
     * Obtener líneas de negocio (negocios) para la lista desplegable.
     */
    public function getNegocios() {
        return $this->model->getNegocios();
    }

    /**
     * Obtener medios de contacto para la lista desplegable.
     */
    public function getContactos() {
        return $this->model->getContactos();
    }

    /**
     * Obtener estatus para la lista desplegable.
     */
    public function getEstatus() {
        return $this->model->getEstatus();
    }
}
