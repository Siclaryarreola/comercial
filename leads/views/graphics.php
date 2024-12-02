<?php
$activePage = 'graphics';
require_once('components/header.php');
require_once('../controllers/graphicsController.php');

// Instancia del controlador y obtención de datos
$graphicsController = new GraphicsController();
$metrics = $graphicsController->getMetrics();
$conversionData = $graphicsController->getConversionData();
$leads = $graphicsController->getDetailedLeads();
$branchData = $graphicsController->getBranchData();
$contactData = $graphicsController->getContactData();

?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<main class="container-fluid mt-4">
    <h1 class="text-center">REPORTE GENERACIÓN DE DEMANDA 2024</h1>

    <!-- Filtros -->
    <div class="d-flex justify-content-between align-items-center my-4">
        <div>
            <button class="btn btn-primary btn-sm">Selecciona un periodo</button>
            <button class="btn btn-secondary btn-sm">Sucursal</button>
            <button class="btn btn-secondary btn-sm">Línea de Negocio</button>
        </div>
    </div>

    <!-- Paneles de métricas -->
    <div class="row text-center">
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body">
                    <h5><i class="fas fa-users"></i> Leads Totales</h5>
                    <h3><?= $metrics['leads_totales'] ?? 0; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body">
                    <h5><i class="fas fa-dollar-sign"></i> Total Cotizado</h5>
                    <h3>$<?= number_format($metrics['total_cotizado'] ?? 0, 2); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body">
                    <h5><i class="fas fa-chart-line"></i> Ventas Totales</h5>
                    <h3>$<?= number_format($metrics['ventas_totales'] ?? 0, 2); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body">
                    <h5><i class="fas fa-times-circle"></i> Total Perdido</h5>
                    <h3>$<?= number_format($metrics['total_perdido'] ?? 0, 2); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de conversión de leads -->
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5>Conversión de Leads</h5>
                    <div class="row text-center">
                        <?php foreach ($conversionData as $key => $value): ?>
                            <div class="col-md-2"><?= ucfirst($key) ?>: <?= $value; ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasa de efectividad -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Tasa de Efectividad</h5>
                    <h3><?= $metrics['tasa_efectividad'] ?? '0.00'; ?>%</h3>
                    <div class="progress mt-2" style="height: 20px;">
                        <div class="progress-bar bg-success" style="width: <?= $metrics['tasa_efectividad'] ?? 0; ?>%;"> <?= $metrics['tasa_efectividad'] ?? '0.00'; ?>%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-center">Principales Sucursales</h5>
                    <canvas id="branchChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-center">Medios de Contacto</h5>
                    <canvas id="contactChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Información detallada -->
    <div class="table-responsive mt-4">
        <table class="table table-bordered table-striped">
            <thead>
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
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Datos para el gráfico de sucursales
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

    // Datos para el gráfico de medios de contacto
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
