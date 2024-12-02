<?php
require_once('../models/graphicsModel.php');


class GraphicsController {
    private $graphicsModel;

    public function __construct() {
        $this->graphicsModel = new GraphicsModel();
    }

    public function getGeneradores() {
        return $this->graphicsModel->getGeneradoresDemanda();
    }

    public function getSucursales() {
        return $this->graphicsModel->getSucursales();
    }

    public function getPeriodos() {
        return $this->graphicsModel->getPeriodos();
    }

    public function getMediosDeContacto() {
        return $this->graphicsModel->getMediosDeContacto();
    }

    public function getEtapasLeads() {
        return $this->graphicsModel->getEtapasLeads();
    }

    public function getConversionLeads() {
        return $this->graphicsModel->getConversionLeads();
    }

    public function getLeadsDetalle() {
        // Nuevo método para obtener detalles de los leads
        return $this->graphicsModel->getLeadsDetalle();
    }
}

?>
