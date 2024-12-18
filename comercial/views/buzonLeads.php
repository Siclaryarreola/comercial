<?php
$activePage = 'buzonLeads';
include('components/header.php');
require_once('../controllers/buzonController.php');

$user = $_SESSION['user'] ?? null;
$gerenteId = $user['id_usuario'] ?? null;
$rol = $user['rol'] ?? 'usuario';

$clienteFiltro = $_GET['cliente'] ?? '';
$estatusFiltro = $_GET['estatus'] ?? '';

try {
    $controller = new BuzonController();

    $periodos = $controller->getPeriodos();
    $sucursales = $controller->getSucursales();
    $gerentes = $controller->getGerentes();
    $negocios = $controller->getNegocios();
    $mediosContacto = $controller->getContactos();
    $estatusCotizacion = $controller->getEstatus();

    if ($rol === 'gerente_sucursal') {
        $pendingLeads = $controller->getLeadsForGerente($gerenteId, $clienteFiltro, $estatusFiltro);
    } else {
        $pendingLeads = $controller->getPendingLeads($clienteFiltro, $estatusFiltro);
    }
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    $pendingLeads = [];
}
?>

<main class="container mt-2">
    <div class="card">
        <div class="card-header text-black p-2">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="text-center mb-0 flex-grow-1" style="font-size:1.5rem;">Buzón de Leads Pendientes</h1>
                <div class="d-flex">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addLeadModal">Añadir Lead</button>
                    <button class="btn btn-secondary btn-sm ml-2" data-toggle="modal" data-target="#historialModal">Ver Historial</button>
                </div>
            </div>
        </div>

        <div class="card-body p-2">
            <div class="mb-2">
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
            </div>

 <?php if (!empty($pendingLeads)): ?>
    <div style="overflow-x: auto; white-space: nowrap;">
        <table class="table table-bordered table-striped table-sm">
            <thead class="thead-dark" style="font-size:0.9rem;">
                <tr>
                    <th>Nombre del Cliente</th>
                    <th>Nombre del Contacto</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Línea de Negocio</th>
                    <th>Giro Empresarial</th>
                    <th>Medio de Contacto</th>
                    <th>Periodo</th>
                    <th>Sucursal</th>
                    <th>Gerente Responsable</th>
                    <th>Monto Cotización</th>
                    <th>Estatus</th>
                    <th>Notas</th>
                    <th>Archivo Cotización</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody style="font-size:0.9rem;">
                <?php foreach ($pendingLeads as $lead): ?>
                    <tr>
                        <td><?= htmlspecialchars($lead['nombre_cliente'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($lead['nombre_contacto'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($lead['correo'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($lead['telefono'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($lead['linea_negocio'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($lead['giro'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($lead['medio_contacto'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($lead['periodo'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($lead['sucursal'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($lead['gerente_responsable'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($lead['monto_cotizacion'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($lead['estatus'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($lead['notas'] ?? 'N/A') ?></td>
                        <td>
                            <?php if (!empty($lead['archivo_cotizacion'])): ?>
                                <a href="uploads/<?= urlencode($lead['archivo_cotizacion']) ?>" target="_blank">Ver Archivo</a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editLeadModal"
                                data-lead-id="<?= htmlspecialchars($lead['id'] ?? '') ?>"
                                data-contacto="<?= htmlspecialchars($lead['nombre_contacto'] ?? '') ?>"
                                data-telefono="<?= htmlspecialchars($lead['telefono'] ?? '') ?>"
                                data-correo="<?= htmlspecialchars($lead['correo'] ?? '') ?>"
                                data-estatus="<?= htmlspecialchars($lead['estatus'] ?? '') ?>">
                                Editar
                            </button>
                            <a href="completeLead.php?id=<?= htmlspecialchars($lead['id'] ?? '') ?>" class="btn btn-success btn-sm">Completado</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info text-center p-2 mb-2" style="font-size:0.9rem;">
        No hay leads pendientes por terminar.
    </div>
<?php endif; ?>


<!-- Modal Añadir Lead -->
<div class="modal fade" id="addLeadModal" tabindex="-1" role="dialog" aria-labelledby="addLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document"> 
        <div class="modal-content">
            <form action="addLead.php" method="post" enctype="multipart/form-data"> 
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="addLeadModalLabel" style="font-size:1.1rem;">Añadir Nuevo Lead</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="padding:0.5rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body p-2">
                    <div class="row">
                        <!-- Primera columna -->
                        <div class="col-md-6">
                            <div class="form-group mb-1">
                                <label>Periodo:</label>
                                <select name="periodo" class="form-control form-control-sm" required>
                                    <option value="">Seleccione un periodo</option>
                                    <?php foreach ($periodos as $p): ?>
                                        <option value="<?= htmlspecialchars($p['id_periodo']) ?>"><?= htmlspecialchars($p['periodo']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group mb-1">
                                <label>Sucursal:</label>
                                <select name="sucursal" class="form-control form-control-sm" required>
                                    <option value="">Seleccione una sucursal</option>
                                    <?php foreach ($sucursales as $s): ?>
                                        <option value="<?= htmlspecialchars($s['id_sucursales']) ?>"><?= htmlspecialchars($s['sucursal']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group mb-1">
                                <label>Gerente Responsable:</label>
                                <select name="gerente_responsable" class="form-control form-control-sm">
                                    <option value="">Seleccione un gerente</option>
                                    <?php foreach ($gerentes as $g): ?>
                                        <option value="<?= htmlspecialchars($g['id_gerente']) ?>"><?= htmlspecialchars($g['gerente']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group mb-1">
                                <label>Medio de Contacto:</label>
                                <select name="medio_contacto" class="form-control form-control-sm">
                                    <option value="">Seleccione un medio</option>
                                    <?php foreach ($mediosContacto as $m): ?>
                                        <option value="<?= htmlspecialchars($m['id_contacto']) ?>"><?= htmlspecialchars($m['contacto']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group mb-1">
                                <label>Monto de Cotización:</label>
                                <input type="number" step="0.01" name="monto_cotizacion" class="form-control form-control-sm">
                            </div>
                            
                            <div class="form-group mb-1">
                                <label>Notas:</label>
                                <textarea name="notas" class="form-control form-control-sm" rows="2"></textarea>
                            </div>

                            <div class="form-group mb-1">
                                <label>Archivo de Cotización (PDF, DOC, etc.):</label>
                                <input type="file" name="archivo_cotizacion" class="form-control-file" style="font-size:0.9rem;">
                            </div>
                        </div>

                        <!-- Segunda columna -->
                        <div class="col-md-6">
                            <div class="form-group mb-1">
                                <label>Nombre del Cliente (Empresa):</label>
                                <input type="text" name="empresa" class="form-control form-control-sm" required>
                            </div>

                            <div class="form-group mb-1">
                                <label>Giro Comercial:</label>
                                <input type="text" name="giro" class="form-control form-control-sm">
                            </div>

                            <div class="form-group mb-1">
                                <label>Nombre del Contacto:</label>
                                <input type="text" name="contacto" class="form-control form-control-sm" required>
                            </div>

                            <div class="form-group mb-1">
                                <label>Correo Electrónico:</label>
                                <input type="email" name="correo" class="form-control form-control-sm">
                            </div>

                            <div class="form-group mb-1">
                                <label>Teléfono:</label>
                                <input type="text" name="telefono" class="form-control form-control-sm">
                            </div>

                            <div class="form-group mb-1">
                                <label>Línea de Negocio de Interés:</label>
                                <select name="linea_negocio" class="form-control form-control-sm">
                                    <option value="">Seleccione una línea de negocio</option>
                                    <?php foreach ($negocios as $neg): ?>
                                        <option value="<?= htmlspecialchars($neg['id_negocio']) ?>"><?= htmlspecialchars($neg['negocio']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group mb-1">
                                <label>Estatus de Cotización:</label>
                                <select name="estatus_cotizacion" class="form-control form-control-sm">
                                    <option value="">Seleccione el estatus</option>
                                    <?php foreach ($estatusCotizacion as $e): ?>
                                        <option value="<?= htmlspecialchars($e['id_estatus']) ?>"><?= htmlspecialchars($e['estatus']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer p-2">
                    <button type="submit" class="btn btn-primary btn-sm">Guardar Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Historial -->
<div class="modal fade" id="historialModal" tabindex="-1" role="dialog" aria-labelledby="historialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 style="font-size:1.1rem;" id="historialModalLabel">Historial de Cambios</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="padding:0.5rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-2">
                <!-- Aquí colocas la lógica para cargar el historial dinámicamente -->
                <!-- Ejemplo estático: -->
                <div class="alert alert-info p-1 mb-1" style="font-size:0.9rem;">
                    Aquí se mostrará el historial de cambios.
                </div>
                
                <table class="table table-sm table-bordered" style="font-size:0.85rem;">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Campo</th>
                            <th>Valor Anterior</th>
                            <th>Valor Nuevo</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>


<!-- Modal Editar Lead -->
<div class="modal fade" id="editLeadModal" tabindex="-1" role="dialog" aria-labelledby="editLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="editLead.php" method="post" id="editLeadForm">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="editLeadModalLabel" style="font-size:1.1rem;">Editar Lead</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="padding:0.5rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-2">
                    <input type="hidden" name="id" id="editLeadId">
                    <div class="form-group mb-1">
                        <label>Cliente:</label>
                        <input type="text" name="contacto" class="form-control form-control-sm" id="editLeadContacto" required>
                    </div>
                    <div class="form-group mb-1">
                        <label>Teléfono:</label>
                        <input type="text" name="telefono" class="form-control form-control-sm" id="editLeadTelefono">
                    </div>
                    <div class="form-group mb-1">
                        <label>Correo:</label>
                        <input type="email" name="correo" class="form-control form-control-sm" id="editLeadCorreo">
                    </div>
                    <div class="form-group mb-1">
                        <label>Estatus:</label>
                        <select name="estatus" class="form-control form-control-sm" id="editLeadEstatus">
                            <option value="1">Pendiente</option>
                            <option value="2">En Proceso</option>
                            <option value="3">Completado</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <button type="submit" class="btn btn-primary btn-sm">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$('#editLeadModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var leadId = button.data('lead-id');
    var contacto = button.data('contacto');
    var telefono = button.data('telefono');
    var correo = button.data('correo');
    var estatus = button.data('estatus');

    $('#editLeadId').val(leadId);
    $('#editLeadContacto').val(contacto);
    $('#editLeadTelefono').val(telefono);
    $('#editLeadCorreo').val(correo);
    $('#editLeadEstatus').val(estatus);
});
</script>

<?php include('components/footer.php'); ?>
