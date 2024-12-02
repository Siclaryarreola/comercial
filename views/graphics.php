<?php 
$activePage = 'graphics';
include('components/header.php');
require_once('../controllers/graphicsController.php');

$controller = new GraphicsController();

// Obtener datos dinámicos desde la base de datos
$generadores = $controller->getGeneradores();
$sucursales = $controller->getSucursales();
$periodos = $controller->getPeriodos();
$medios = $controller->getMediosDeContacto();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Generación de Demanda <?= date('Y') ?></title>
    <link rel="stylesheet" href="../public/css/styleGraphics.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .left-container, .right-container {
            flex: 1;
            min-width: 300px;
        }

        .dropdown-container-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            background-color: #ffffff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .dropdown-container h4 {
            margin-bottom: 5px;
        }

        .dropdown-container select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .charts-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .chart-wrapper {
            flex: 1;
            min-width: 300px;
            padding: 10px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .chart-wrapper canvas {
            max-width: 100%;
            height: 250px;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <h3 class="text-center">Reporte Generación de Demanda <?= date('Y') ?></h3>
        
        <div class="dashboard-container">
            <!-- Contenedor izquierdo -->
            <div class="left-container">
                <div class="dropdown-container-group">
                    <div class="dropdown-container">
                        <h4>Generador de Demanda</h4>
                        <select>
                            <option>Seleccionar Generador</option>
                            <?php foreach ($generadores as $generador): ?>
                                <option value="<?= $generador['id_usuarios'] ?>"><?= htmlspecialchars($generador['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="dropdown-container">
                        <h4>Periodos</h4>
                        <select>
                            <option>Seleccionar Periodo</option>
                            <?php foreach ($periodos as $periodo): ?>
                                <option value="<?= $periodo['id_periodo'] ?>"><?= htmlspecialchars($periodo['periodo']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="dropdown-container">
                        <h4>Sucursales</h4>
                        <select>
                            <option>Seleccionar Sucursal</option>
                            <?php foreach ($sucursales as $sucursal): ?>
                                <option value="<?= $sucursal['id'] ?>"><?= htmlspecialchars($sucursal['sucursal']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="dropdown-container">
                        <h4>Medios de Contacto</h4>
                        <select>
                            <option>Seleccionar Medio</option>
                            <?php foreach ($medios as $medio): ?>
                                <option value="<?= $medio['id_contacto'] ?>"><?= htmlspecialchars($medio['contacto']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Contenedor derecho -->
            <div class="right-container">
                <div class="charts-container">
                    <div class="chart-wrapper">
                        <h4>Sucursales con Más Leads</h4>
                        <canvas id="chartSucursales"></canvas>
                    </div>
                    <div class="chart-wrapper">
                        <h4>Leads por Medio de Contacto</h4>
                        <canvas id="chartMedios"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gráfica de sucursales
        const ctxSucursales = document.getElementById('chartSucursales').getContext('2d');
        new Chart(ctxSucursales, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($sucursales, 'sucursal')) ?>,
                datasets: [{
                    data: [<?= implode(',', array_column($sucursales, 'conteo')) ?>], // Sustituye con datos dinámicos
                    backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Gráfica de medios de contacto
        const ctxMedios = document.getElementById('chartMedios').getContext('2d');
        new Chart(ctxMedios, {
            type: 'pie',
            data: {
                labels: <?= json_encode(array_column($medios, 'contacto')) ?>,
                datasets: [{
                    data: [<?= implode(',', array_column($medios, 'conteo')) ?>], // Sustituye con datos dinámicos
                    backgroundColor: ['#ffce56', '#4bc0c0', '#ff6384']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
</body>
</html>
