<?php 
$activePage = 'leads';
include('components/header.php'); 
require_once('../controllers/leadsController.php');

// Instancia del controlador y carga de datos
$leadsController = new LeadsController();
$leads = $leadsController->index();
$dropdownData = $leadsController->getDropdownData();
$usuarioRol = $_SESSION['user']['rol'] ?? ''; // Obtener el rol del usuario
?>

<!-- Incluir CSS de DataTables y Bootstrap -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="../public/css/styleDashboard.css">

<!-- Incluir jQuery y DataTables -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js"></script>

<main class="container-fluid mt-5">
    <h1>Leads</h1>
    <div class="mb-3">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addLeadModal">Agregar nuevo lead</button>
    </div>

    <table class="table table-striped" id="leadsTable">
        <thead>
            <tr>
                <th>Empresa</th>
                <th>Localidad</th>
                <th>Giro</th>
                <th>Sucursal</th>
                <th>Periodo</th>
                <th>Estatus</th>
                <th>Gerente</th>
                <th>Medio Contacto</th>
                <th>Negocio</th>
                <th>Archivo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($leads as $lead): ?>
                <tr>
                    <td><?= htmlspecialchars($lead['empresa'] ?? 'N/D') ?></td>
                    <td><?= htmlspecialchars($lead['localidad'] ?? 'N/D') ?></td>
                    <td><?= htmlspecialchars($lead['giro'] ?? 'N/D') ?></td>
                    <td><?= htmlspecialchars($lead['sucursal'] ?? 'N/D') ?></td>
                    <td><?= htmlspecialchars($lead['periodo'] ?? 'N/D') ?></td>
                    <td><?= htmlspecialchars($lead['estatus'] ?? 'N/D') ?></td>
                    <td><?= htmlspecialchars($lead['gerente'] ?? 'N/D') ?></td>
                    <td><?= htmlspecialchars($lead['medio_contacto'] ?? 'N/D') ?></td>
                    <td><?= htmlspecialchars($lead['negocio'] ?? 'N/D') ?></td>
                    <td>
                        <?php if (!empty($lead['archivo'])): ?>
                            <a href="../Leads/<?= htmlspecialchars($lead['archivo']) ?>" target="_blank" download>Descargar archivo</a>
                        <?php else: ?>
                            N/D
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="viewLead.php?id=<?= $lead['id'] ?>" class="btn btn-info btn-sm">Detalle</a>
                        <a href="editLead.php?id=<?= $lead['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="deleteLead.php?id=<?= $lead['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Seguro que desea eliminar?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<!-- Modal para Agregar Nuevo Lead -->
<div class="modal fade" id="addLeadModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar nuevo lead</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addLeadForm" action="../controllers/leadsController.php?action=addLead" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Columna 1 -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="empresa">Empresa</label>
                                <input type="text" class="form-control" id="empresa" name="empresa" required>
                            </div>
                            <div class="form-group">
                                <label for="localidad">Localidad</label>
                                <input type="text" class="form-control" id="localidad" name="localidad">
                            </div>
                            <div class="form-group">
                                <label for="giro">Giro</label>
                                <input type="text" class="form-control" id="giro" name="giro">
                            </div>
                            <div class="form-group">
                                <label for="sucursal">Sucursal</label>
                                <select class="form-control" id="sucursal" name="sucursal" required>
                                    <option value="" selected>Seleccionar</option>
                                    <?php foreach ($dropdownData['sucursales'] as $sucursal): ?>
                                        <option value="<?= htmlspecialchars($sucursal['id']) ?>"><?= htmlspecialchars($sucursal['sucursal']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <!-- Columna 2 -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="contacto">Contacto</label>
                                <input type="text" class="form-control" id="contacto" name="contacto">
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono">
                            </div>
                            <div class="form-group">
                                <label for="correo">Correo</label>
                                <input type="email" class="form-control" id="correo" name="correo">
                            </div>
                            <div class="form-group">
                                <label for="medio_contacto">Medio de Contacto</label>
                                <select class="form-control" id="medio_contacto" name="medio_contacto" required>
                                    <option value="" selected>Seleccionar</option>
                                    <?php foreach ($dropdownData['contactos'] as $contacto): ?>
                                        <option value="<?= htmlspecialchars($contacto['id_contacto']) ?>"><?= htmlspecialchars($contacto['contacto']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <!-- Columna 3 -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fecha_prospeccion">Fecha de Prospección</label>
                                <input type="date" class="form-control" id="fecha_prospeccion" name="fecha_prospeccion">
                            </div>
                            <div class="form-group">
                                <label for="periodo">Periodo</label>
                                <select class="form-control" id="periodo" name="periodo" required>
                                    <option value="" selected>Seleccionar</option>
                                    <?php foreach ($dropdownData['periodos'] as $periodo): ?>
                                        <option value="<?= htmlspecialchars($periodo['id_periodo']) ?>"><?= htmlspecialchars($periodo['periodo']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gerente">Gerente Responsable</label>
                                <select class="form-control" id="gerente" name="gerente" required>
                                    <option value="" selected>Seleccionar</option>
                                    <?php foreach ($dropdownData['gerentes'] as $gerente): ?>
                                        <option value="<?= htmlspecialchars($gerente['id_gerente']) ?>"><?= htmlspecialchars($gerente['gerente']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="estatus">Estatus</label>
                                <select class="form-control" id="estatus" name="estatus" required>
                                    <option value="" selected>Seleccionar</option>
                                    <?php foreach ($dropdownData['estatus'] as $estatus): ?>
                                        <option value="<?= htmlspecialchars($estatus['id_estatus']) ?>"><?= htmlspecialchars($estatus['estatus']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="linea_negocio">Negocio</label>
                                <select class="form-control" id="linea_negocio" name="linea_negocio" required>
                                    <option value="" selected>Seleccionar</option>
                                    <?php foreach ($dropdownData['negocios'] as $negocio): ?>
                                        <option value="<?= htmlspecialchars($negocio['id_negocio']) ?>"><?= htmlspecialchars($negocio['negocio']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Agregar campos adicionales según el rol -->
                    <?php if ($usuarioRol == 'gerente' || $usuarioRol == 'administrador'): ?>
                        <div class="form-group">
                            <label for="archivo">Archivo</label>
                            <input type="file" class="form-control-file" id="archivo" name="archivo">
                        </div>
                        <div class="form-group">
                            <label for="cotizacion">Cotización</label>
                            <input type="text" class="form-control" id="cotizacion" name="cotizacion">
                        </div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-success btn-block">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#leadsTable').DataTable();
});
</script>
<?php include('components/footer.php'); ?>
