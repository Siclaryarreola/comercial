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

    <!-- Contenedor Principal de Filtros y Gráficas -->
    <div class="row">
        <!-- Contenedor de Filtros y Gráficas -->
        <div class="col-md-4">
            <!-- Contenedor de Filtros -->
            <div class="card mb-3">
                <div class="card-body filters-container">
                    <h5 class="text-center">Filtros</h5>
                    <div class="row filters-group">
                        <div class="col-md-6 mb-3">
                            <label for="generador">Generador de Demanda</label>
                            <select id="generador" class="form-control">
                                <option value="" selected>Seleccionar</option>
                                <?php foreach ($generadores as $generador): ?>
                                    <option value="<?= $generador['id_usuarios']; ?>"><?= htmlspecialchars($generador['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="periodo">Periodo</label>
                            <select id="periodo" class="form-control">
                                <option value="" selected>Seleccionar</option>
                                <?php foreach ($periodos as $periodo): ?>
                                    <option value="<?= $periodo['id_periodo']; ?>"><?= htmlspecialchars($periodo['periodo']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sucursal">Sucursal</label>
                            <select id="sucursal" class="form-control">
                                <option value="" selected>Seleccionar</option>
                                <?php foreach ($sucursales as $sucursal): ?>
                                    <option value="<?= $sucursal['id_sucursales']; ?>"><?= htmlspecialchars($sucursal['sucursal']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lineaNegocio">Línea de Negocio</label>
                            <select id="lineaNegocio" class="form-control">
                                <option value="" selected>Seleccionar</option>
                                <option value="MPS">MPS</option>
                                <option value="Etiquetado y Codificado">Etiquetado y Codificado</option>
                                <option value="Tecnología">Tecnología</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficas -->
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

        <!-- Contenedor de Información Detallada -->
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
                label: 'Queretaro',
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
