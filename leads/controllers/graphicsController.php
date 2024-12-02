<?php
require_once('../models/graphicsModel.php');

class GraphicsController {
    private $graphicsModel;

    public function __construct() {
        $this->graphicsModel = new GraphicsModel();
    }

    public function getMetrics() {
        $conversionData = $this->graphicsModel->getConversionLeads();
        $totalLeads = $conversionData['totalLeads'] ?? 0;
        $totalCotizado = $conversionData['totalCotizado'] ?? 0;
        $ventasTotales = $conversionData['ventasTotales'] ?? 0;
        $totalPerdido = $conversionData['totalPerdido'] ?? 0;
        $tasaEfectividad = $totalLeads > 0 ? ($ventasTotales / $totalLeads) * 100 : 0;

        return [
            'leads_totales' => $totalLeads,
            'total_cotizado' => $totalCotizado,
            'ventas_totales' => $ventasTotales,
            'total_perdido' => $totalPerdido,
            'tasa_efectividad' => $tasaEfectividad,
        ];
    }

    public function getConversionData() {
        return $this->graphicsModel->getEtapasLeads();
    }

    public function getDetailedLeads() {
        return $this->graphicsModel->getLeadsDetalle();
    }

    public function getBranchData() {
        $sucursales = $this->graphicsModel->getSucursales();
        $branchCounts = [];
        foreach ($sucursales as $sucursal) {
            $branchCounts[$sucursal['sucursal']] = rand(1, 100); // Simular datos si no están implementados
        }
        return $branchCounts;
    }

    public function getContactData() {
        $contactos = $this->graphicsModel->getMediosDeContacto();
        $contactCounts = [];
        foreach ($contactos as $contacto) {
            $contactCounts[$contacto['contacto']] = rand(1, 100); // Simular datos si no están implementados
        }
        return $contactCounts;
    }
}
