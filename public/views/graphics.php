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
            <div class="row">
    <!-- Conversión de Leads -->
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm p-3">
            <h4 class="text-center text-primary">CONVERSIÓN DE LEADS</h4>
            <div class="row text-center">
                <div class="col-md-3">
                    <p><span class="emoji">👥</span> LEADS TOTALES</p>
                    <h4><?= htmlspecialchars($conversionData['totalLeads'] ?? 0) ?></h4>
                </div>
                <div class="col-md-3">
                    <p><span class="emoji">💰</span> TOTAL COTIZADO</p>
                    <h4>$<?= number_format($conversionData['totalCotizado'] ?? 0, 2) ?></h4>
                </div>
                <div class="col-md-3">
                    <p><span class="emoji">📈</span> VENTAS TOTALES</p>
                    <h4>$<?= number_format($conversionData['ventasTotales'] ?? 0, 2) ?></h4>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $conversionData['ventasPorcentaje'] ?? 0 ?>%;" aria-valuenow="<?= $conversionData['ventasPorcentaje'] ?? 0 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <p><span class="emoji">❌</span> TOTAL PERDIDO</p>
                    <h4>$<?= number_format($conversionData['totalPerdido'] ?? 0, 2) ?></h4>
                    <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $conversionData['perdidoPorcentaje'] ?? 0 ?>%;" aria-valuenow="<?= $conversionData['perdidoPorcentaje'] ?? 0 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Etapas de Leads -->
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm p-3">
            <h4 class="text-center text-success">ETAPAS DE LEADS</h4>
            <div class="row text-center">
                <?php foreach ($etapasData as $etapa): ?>
                    <div class="col-md-2">
                        <p><?= htmlspecialchars($etapa['nombre']) ?></p>
                        <h4><?= htmlspecialchars($etapa['cantidad']) ?></h4>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Desempeño del Lead -->
    <div class="col-md-12">
        <div class="card shadow-sm p-3">
            <h4 class="text-center text-secondary">DESEMPEÑO DEL LEAD</h4>
            <div class="row text-center">
                <div class="col-md-6">
                    <p><span class="emoji">⭐</span> TASA DE EFECTIVIDAD (Promedio del 20%)</p>
                    <h4><?= number_format($conversionData['tasaEfectividad'] ?? 0, 2) ?>%</h4>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $conversionData['tasaEfectividad'] ?? 0 ?>%;" aria-valuenow="<?= $conversionData['tasaEfectividad'] ?? 0 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <p><span class="emoji">✅</span> CALIFICACIÓN PROMEDIO DE LEADS</p>
                    <h4><?= number_format($conversionData['calificacionPromedio'] ?? 0, 2) ?>%</h4>
                    <div class="progress">
                        <div class="progress-bar bg-info" role="progressbar" style="width: <?= $conversionData['calificacionPromedio'] ?? 0 ?>%;" aria-valuenow="<?= $conversionData['calificacionPromedio'] ?? 0 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
<?php include('components/footer.php'); ?>
