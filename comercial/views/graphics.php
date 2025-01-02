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

$leads = $graphicsController->getLeadsDetalle();
$branchData = $graphicsController->getBranchData();
$contactData = $graphicsController->getContactData();
$conversionData = $graphicsController->getConversionLeads();

// Datos para gráficos
$branchDataFormatted = [];
foreach ($branchData as $branch) {
    $branchDataFormatted[$branch['sucursal']] = $branch['conteo'];
}

$contactDataFormatted = [];
foreach ($contactData as $contact) {
    $contactDataFormatted[$contact['contacto']] = $contact['conteo'];
}
?>


    <div class="row align-items-start">
        <!-- Columna Izquierda: Filtros y Gráficas -->
        <div class="col-md-4">

            <div class="card mb-3">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center mb-2" style="gap: 0.5rem;">
                        <input type="text" id="filterText" class="form-control form-control-sm" placeholder="Filtrar por texto..." style="max-width: 150px;">
                        <div class="d-flex align-items-center" style="gap:0.5rem;">
                            <input type="date" id="startDate" class="form-control form-control-sm" style="max-width: 130px;">
                            <input type="date" id="endDate" class="form-control form-control-sm" style="max-width: 130px;">
                        </div>
                        
                        <label for="demandGenerator" class="form-label mb-0" style="font-size: 0.875rem;">Generador de Demanda:</label>
                            <select id="demandGenerator" class="form-select form-select-sm" style="max-width: 200px;">
                                <option value="">Seleccione...</option>
                            </select>

                        <button class="btn btn-primary btn-sm" id="applyFiltersBtn">Aplicar</button>
                    </div>
                    
                    <!-- Gráfica de Medios de Contacto -->
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="text-center">Medios de Contacto</h5>
                                        <div style="max-width:200px; margin:0 auto;">
                                            <canvas id="contactChart" width="200" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <h5 class="text-center mb-2">Principales Sucursales</h5>
                    <div style="max-width:300px; margin:0 auto;">
                        <canvas id="branchChart" width="300" height="300"></canvas>
                    </div>
                </div>
            </div>
                    
                    
                <!-- Columna Derecha: Información Detallada -->
                <div class="col-md-8">
                    
                <div class="card shadow-sm p-3 mb-4">
                    <h1 id="report-title" class="text-center mb-4">Reporte Generación de Demanda</h1>

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
                            <h4><?= number_format($conversionData['ventasTotales'] ?? 0, 2) ?></h4>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?= $conversionData['ventasPorcentaje'] ?? 0 ?>%;" aria-valuenow="<?= $conversionData['ventasPorcentaje'] ?? 0 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <p><span class="emoji">❌</span> TOTAL PERDIDO</p>
                            <h4><?= number_format($conversionData['totalPerdido'] ?? 0, 2) ?></h4>
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $conversionData['perdidoPorcentaje'] ?? 0 ?>%;" aria-valuenow="<?= $conversionData['perdidoPorcentaje'] ?? 0 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                    </div>
                </div>

        
            <!-- ETAPAS DE LEADS -->
            <div class="card shadow-sm p-3 mb-4">
                <h4 class="text-center text-success">ETAPAS DE LEADS</h4>
                <div class="row text-center">
                    <?php
                    $etapasPosibles = ['Contactado','Presentación','Interesado','Cotización Enviada'];
                    $etapasMap = array_fill_keys($etapasPosibles, 0);
                    
                    if (!empty($etapasLeads)) {
                        foreach ($etapasLeads as $etapa) {
                            $etapasMap[$etapa['estatus']] = $etapa['conteo'];
                        }
                    }
                    
                    foreach ($etapasMap as $nombreEtapa => $conteo): ?>
                        <div class="col-md-2 mb-2">
                            <div class="card p-2">
                                <h5 class="text-uppercase"><?= htmlspecialchars($nombreEtapa) ?></h5>
                                <h4 class="font-weight-bold"><?= htmlspecialchars($conteo) ?></h4>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- DESEMPEÑO DEL LEAD -->
            <div class="card shadow-sm p-3 mb-4">
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
            
            
        </div> <!-- Fin col-md-8 -->
    </div> <!-- Fin row principal -->

    <!-- Información Detallada - Tabla a lo largo debajo de todo -->
    <div class="container-fluid mt-4">
        <div class="card mb-4">
            <div class="card-body p-2">
                <div class="table-responsive d-flex justify-content-center" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-bordered table-striped" style="margin:0 auto; width:100%;">
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
</main>

<?php include('components/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    window.branchLabels = <?= json_encode(array_keys($branchDataFormatted)) ?>;
    window.branchData = <?= json_encode(array_values($branchDataFormatted)) ?>;
    window.contactLabels = <?= json_encode(array_keys($contactDataFormatted)) ?>;
    window.contactData = <?= json_encode(array_values($contactDataFormatted)) ?>;
</script>
<script>
    window.branchLabels = <?= json_encode(array_keys($branchDataFormatted)) ?>;
    window.branchData = <?= json_encode(array_values($branchDataFormatted)) ?>;
    window.contactDataFormatted = <?= json_encode($contactDataFormatted) ?>;
</script>
<script src="../public/js/graphics.js"></script>
<script src="../public/js/graphics.js"></script>

<script>
    // Obtener el año actual
    const currentYear = new Date().getFullYear();

    // Seleccionar el título por su ID
    const reportTitle = document.getElementById('report-title');

    // Actualizar el contenido del título con el año actual
    reportTitle.textContent += ` ${currentYear}`;
</script>


<Script>
document.addEventListener("DOMContentLoaded", function () {
    fetch('../controllers/graphicsController.php?method=fetchGeneradoresDemanda')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const demandGeneratorSelect = document.getElementById('demandGenerator');
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id_usuarios;
                option.textContent = item.nombre;
                demandGeneratorSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error cargando los generadores de demanda:', error));
});

</Script>
