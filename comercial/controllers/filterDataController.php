<?php
require_once('../controllers/graphicsController.php');
$graphicsController = new GraphicsController();

// Obtener los datos enviados desde el cliente
$data = json_decode(file_get_contents('php://input'), true);
$generador = $data['generador'] ?? null;
$periodo = $data['periodo'] ?? null;
$sucursal = $data['sucursal'] ?? null;
$lineaNegocio = $data['lineaNegocio'] ?? null;

// Filtrar los datos en función de los filtros seleccionados
$branchData = $graphicsController->getFilteredBranchData($generador, $periodo, $sucursal, $lineaNegocio);
$contactData = $graphicsController->getFilteredContactData($generador, $periodo, $sucursal, $lineaNegocio);

// Responder con los datos en formato JSON
header('Content-Type: application/json');
echo json_encode([
    'branchData' => $branchData,
    'contactData' => $contactData,
]);
