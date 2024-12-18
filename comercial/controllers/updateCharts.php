<?php
require_once('../controllers/graphicsController.php');

$controller = new GraphicsController();

// Recibir datos enviados por POST
$data = json_decode(file_get_contents('php://input'), true);

// Procesar filtros
$generador = $data['generador'] ?? null;
$periodo = $data['periodo'] ?? null;
$sucursal = $data['sucursal'] ?? null;
$medio = $data['medio'] ?? null;

// Obtener datos para gráficas
$sucursales = $controller->getChartDataForSucursales($generador, $periodo, $sucursal, $medio);
$medios = $controller->getChartDataForMedios($generador, $periodo, $sucursal, $medio);

// Devolver datos en formato JSON
echo json_encode(['sucursales' => $sucursales, 'medios' => $medios]);
