<?php
require_once('../models/graphicsModel.php');

class graphicsController 
{
    private $graphicsModel;

    public function __construct() {
        $this->graphicsModel = new GraphicsModel();
    }

    public function getGeneradoresDemanda() {
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
    $conversionData = $this->graphicsModel->getConversionLeads();

    // Cálculo de porcentajes
    $totalCotizado = floatval($conversionData['totalCotizado'] ?? 0);
    $ventasTotales = floatval($conversionData['ventasTotales'] ?? 0);
    $totalPerdido = floatval($conversionData['totalPerdido'] ?? 0);

    $ventasPorcentaje = ($totalCotizado > 0) ? ($ventasTotales / $totalCotizado) * 100 : 0;
    $perdidoPorcentaje = ($totalCotizado > 0) ? ($totalPerdido / $totalCotizado) * 100 : 0;

    $conversionData['ventasPorcentaje'] = $ventasPorcentaje;
    $conversionData['perdidoPorcentaje'] = $perdidoPorcentaje;

    return $conversionData;
}




    public function getLeadsDetalle() {
        return $this->graphicsModel->getLeadsDetalle();
    }
    
    public function getBranchData() {
        return $this->graphicsModel->getBranchData();
    }

    public function getContactData() {
        return $this->graphicsModel->getContactData();
    }
    
    
    // Métodos de datos filtrados
    public function getFilteredBranchData($generador, $periodo, $sucursal, $lineaNegocio) {
        return $this->graphicsModel->getFilteredBranchData($generador, $periodo, $sucursal, $lineaNegocio);
    }

    public function getFilteredContactData($generador, $periodo, $sucursal, $lineaNegocio) {
        return $this->graphicsModel->getFilteredContactData($generador, $periodo, $sucursal, $lineaNegocio);
    }
    
    

}
?>
