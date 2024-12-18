<?php 
$activePage = 'leads';
include('components/header.php'); 
require_once('../controllers/leadsController.php');

$leadsController = new LeadsController();
$leads = $leadsController->index();
$dropdownData = $leadsController->getDropdownData();
$etapasLeads = $leadsController->getEtapasLeads();
$branchData = $leadsController->getBranchData();

$etapasPosibles = ['Contactado', 'Presentación', 'Interesado', 'Cotización Enviada', 'Cerrado-Ganado', 'Cerrado-Perdido'];
$etapasMap = array_fill_keys($etapasPosibles, 0);
foreach ($etapasLeads as $etapa) {
    if (isset($etapasMap[$etapa['estatus']])) {
        $etapasMap[$etapa['estatus']] = $etapa['conteo'];
    }
}

$usuarioRol = $_SESSION['user']['rol'] ?? null;
?>

<link rel="stylesheet" href="../public/css/styleLeads.css">
<main class="container-fluid mt-4">
    <!-- Panel de Estadísticas de Leads y Etapas de Leads -->
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
               <div class="card-body" style="height: 280px;">
                    <canvas id="branchChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-body">
                    <h1 class="text-center">Etapas de Leads</h1>
                    <p class="text-center text-muted">Gestiona y analiza tus leads fácilmente desde este panel.</p>
                    <div class="row g-3 justify-content-center">
                        
                    <?php foreach ($etapasMap as $nombreEtapa => $conteo): ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                            <div class="card p-3 etapas-leads-card text-center">
                                <h5 class="text-uppercase"><?= htmlspecialchars($nombreEtapa) ?></h5>
                                <h4 class="font-weight-bold text-primary"><?= htmlspecialchars($conteo) ?></h4>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Panel de Listado de Leads -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="text-center">Listado de Leads</h4>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Botón de añadir lead, ahora a la izquierda -->
                            <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#addLeadModal" style="background-color: #0d850d; border-color: #0d850d;">Añadir Lead</button>

                            <h5 style="font-size:1.1rem;">Filtrar Leads</h5>
                <form method="GET" class="form-inline">
                    <div class="form-group mr-2 mb-2">
                        <label for="cliente" class="mr-2">Cliente:</label>
                        <input type="text" name="cliente" id="cliente" class="form-control form-control-sm" placeholder="Nombre del cliente" value="<?= htmlspecialchars($clienteFiltro) ?>">
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <label for="estatus" class="mr-2">Estatus:</label>
                        <select name="estatus" id="estatus" class="form-control form-control-sm">
                            <option value="">Todos</option>
                            <option value="1" <?= $estatusFiltro=='1'?'selected':'' ?>>prospecto</option>
                            <option value="2" <?= $estatusFiltro=='4'?'selected':'' ?>>Contactado</option>
                            <option value="3" <?= $estatusFiltro=='3'?'selected':'' ?>>Interesado</option>
                            <option value="4" <?= $estatusFiltro=='4'?'selected':'' ?>>En Seguimiento</option>
                            <option value="5" <?= $estatusFiltro=='5'?'selected':'' ?>>Cotización enviada</option>
                            <option value="6" <?= $estatusFiltro=='6'?'selected':'' ?>>Presentación Realizada</option>
                            <option value="7" <?= $estatusFiltro=='7'?'selected':'' ?>>No Contesta</option>
                            <option value="8" <?= $estatusFiltro=='8'?'selected':'' ?>>No Viable</option>
                            <option value="9" <?= $estatusFiltro=='9'?'selected':'' ?>>Cerrado-Ganado</option>
                            <option value="10" <?= $estatusFiltro=='10'?'selected':'' ?>>Cerrado-Perdido</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
                </form>
                            
                            
                    <!-- Formulario de filtro -->
                </div>
                
                <div class="table-responsive">
                   <table class="table table-striped table-bordered table-hover" id="leadsTable">
    <thead>
        <tr>
            <th>Nombre del Cliente</th>
            <th>Nombre del Contacto</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Fecha</th>
            <th>Periodo</th>
            <th>Gerente</th>
            <th>Sucursal</th>
            <th>Medio</th>
            <th>Estatus</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($leads as $lead): ?>
            <tr data-id="<?= $lead['id'] ?>">
                <td class="editable" data-field="empresa"><?= htmlspecialchars($lead['empresa']) ?></td>
                <td class="editable" data-field="contacto"><?= htmlspecialchars($lead['contacto']) ?></td>
                <td class="editable" data-field="correo"><?= htmlspecialchars($lead['correo']) ?></td>
                <td class="editable" data-field="telefono"><?= htmlspecialchars($lead['telefono']) ?></td>
                <td><?= htmlspecialchars($lead['fecha_generacion']) ?></td>
                <td class="editable-select" data-field="periodo"><?= htmlspecialchars($lead['periodo']) ?></td>
                <td class="editable-select" data-field="gerente"><?= htmlspecialchars($lead['gerente']) ?></td>
                <td class="editable-select" data-field="sucursal"><?= htmlspecialchars($lead['sucursal']) ?></td>
                <td class="editable-select" data-field="medio_contacto"><?= htmlspecialchars($lead['medio_contacto']) ?></td>
                <td class="editable-select" data-field="estatus"><?= htmlspecialchars($lead['estatus']) ?></td>
                <td>
                    <button class="btn btn-primary btn-sm save-lead-btn">Guardar</button>
                    <button class="btn btn-info btn-sm view-history-btn" data-id="<?= $lead['id'] ?>" data-toggle="modal" data-target="#historyModal">Historial</button>
                </td>
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

<!-- Modal Agregar Lead -->
<div class="modal fade" id="addLeadModal" tabindex="-1" aria-labelledby="addLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="addLeadModalLabel">Agregar Nuevo Lead</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <form id="addLeadForm" method="POST">
                    <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            
 <!-- --------------------------------------------------------------------------------- -->    
                            <!-- Columna 1 -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="empresa">Nombre del Cliente</label>
                                    <input type="text" class="form-control" id="empresa" name="empresa">
                                </div>
                                 <div class="form-group">
                                    <label for="contacto">Nombre del Contacto</label>
                                    <input type="text" class="form-control" id="contacto" name="contacto">
                                </div>
                                <div class="form-group">
                                    <label for="correo">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="correo" name="correo">
                                </div>
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono">
                                </div>
                                <div class="form-group">
                                    <label for="giro">Giro</label>
                                    <input type="text" class="form-control" id="giro" name="giro">
                                </div>
                                <div class="form-group">
                                    <label for="notas">Notas</label>
                                    <textarea class="form-control" id="notas" name="notas" rows="3"></textarea>
                                </div>
                            </div>
                            
 <!-- --------------------------------------------------------------------------------- -->    
                            <!-- Columna 2 -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sucursal">Sucursal</label>
                                    <select class="form-control" id="sucursal" name="sucursal" required>
                                        <option value="">Seleccionar</option>
                                        <?php foreach ($dropdownData['sucursales'] ?? [] as $sucursal): ?>
                                            <option value="<?= $sucursal['id_sucursales'] ?>"><?= htmlspecialchars($sucursal['sucursal']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="gerente_responsable">Gerente Responsable</label>
                                    <select class="form-control" id="gerente_responsable" name="gerente_responsable" required>
                                        <option value="">Seleccionar</option>
                                        <?php foreach ($dropdownData['gerentes'] ?? [] as $gerente): ?>
                                            <option value="<?= $gerente['id_gerente'] ?>"><?= htmlspecialchars($gerente['gerente']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="medio_contacto">Medio de Contacto</label>
                                    <select class="form-control" id="medio_contacto" name="medio_contacto" required>
                                        <option value="">Seleccionar</option>
                                        <?php foreach ($dropdownData['contactos'] ?? [] as $contacto): ?>
                                            <option value="<?= $contacto['id_contacto'] ?>"><?= htmlspecialchars($contacto['contacto']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="linea_negocio">Línea de Negocio</label>
                                    <select class="form-control" id="linea_negocio" name="linea_negocio" required>
                                        <option value="">Seleccionar</option>
                                        <?php foreach ($dropdownData['negocios'] ?? [] as $negocio): ?>
                                            <option value="<?= $negocio['id_negocio'] ?>"><?= htmlspecialchars($negocio['negocio']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="estatus">Estatus</label>
                                    <select class="form-control" id="estatus" name="estatus" required>
                                        <option value="">Seleccionar</option>
                                        <?php foreach ($dropdownData['estatus'] ?? [] as $estatus): ?>
                                            <option value="<?= $estatus['id_estatus'] ?>"><?= htmlspecialchars($estatus['estatus']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="periodo">Periodo</label>
                                    <select class="form-control" id="periodo" name="periodo" required>
                                        <option value="">Seleccionar</option>
                                        <?php foreach ($dropdownData['periodos'] ?? [] as $periodo): ?>
                                            <option value="<?= $periodo['id_periodo'] ?>"><?= htmlspecialchars($periodo['periodo']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" style="background-color: #33a633; border-color: #33a633;">Guardar Lead</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
<script>
    var etapasMap = <?= json_encode($etapasMap) ?>;
</script>
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
<script src="../public/js/leads.js"></script>
<?php include('components/footer.php'); ?>