<?php
$activePage = 'graphics';
include('components/header.php');
require_once('../controllers/graphicsController.php');

// Instancia del controlador y obtención de datos
$graphicsController = new GraphicsController();
$generadores = $graphicsController->getGeneradoresDemanda();
$sucursales = $graphicsController->getSucursales();
$periodos = $graphicsController->getPeriodos();
$mediosContacto = $graphicsController->getMediosDeContacto();
$etapasLeads = $graphicsController->getEtapasLeads();
$conversionLeads = $graphicsController->getConversionLeads();
$leads = $graphicsController->getLeadsDetalle();

// Datos para gráficos
$branchData = ['Querétaro' => 67.13, 'Cd. Juárez' => 11.19, 'Puebla' => 10.51];
$contactData = ['WhatsApp' => 58.3, 'Teléfono' => 20.8, 'Correo' => 16.7];
?>

<main class="container-fluid mt-4">
    <h1 class="text-center">REPORTE GENERACIÓN DE DEMANDA 2024</h1>

    <!-- Filtros -->
    <div class="d-flex justify-content-between align-items-center my-4">
        <button class="btn btn-primary btn-sm">Selecciona un periodo</button>
        <button class="btn btn-secondary btn-sm">Sucursal</button>
        <button class="btn btn-secondary btn-sm">Línea de Negocio</button>
    </div>

    <div class="row">
        <!-- Contenedor Izquierdo: Información detallada -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Información Detallada</h5>
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-bordered table-striped">
                            <thead style="position: sticky; top: 0; background-color: #f8f9fa;">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Sucursal</th>
                                    <th>Empresa</th>
                                    <th>Estatus</th>
                                    <th>Línea de Negocio</th>
                                    <th>Comentarios</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($leads as $lead): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($lead['fecha']); ?></td>
                                        <td><?= htmlspecialchars($lead['sucursal']); ?></td>
                                        <td><?= htmlspecialchars($lead['empresa']); ?></td>
                                        <td><?= htmlspecialchars($lead['estatus']); ?></td>
                                        <td><?= htmlspecialchars($lead['linea_negocio']); ?></td>
                                        <td><?= htmlspecialchars($lead['comentarios']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenedor Derecho: Gráficos -->
        <div class="col-md-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="text-center">Principales Sucursales</h5>
                            <canvas id="branchChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-center">Medios de Contacto</h5>
                            <canvas id="contactChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const branchCtx = document.getElementById('branchChart').getContext('2d');
    new Chart(branchCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($branchData)); ?>,
            datasets: [{
                label: 'Porcentaje',
                data: <?= json_encode(array_values($branchData)); ?>,
                backgroundColor: ['#4CAF50', '#FF9800', '#F44336', '#2196F3']
            }]
        }
    });

    const contactCtx = document.getElementById('contactChart').getContext('2d');
    new Chart(contactCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_keys($contactData)); ?>,
            datasets: [{
                data: <?= json_encode(array_values($contactData)); ?>,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
            }]
        }
    });
</script>
