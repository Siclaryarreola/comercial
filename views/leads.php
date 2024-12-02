<?php 
$activePage = 'leads';
include('components/header.php'); 
require_once('../controllers/leadsController.php');

// Instancia del controlador y carga de datos
$leadsController = new LeadsController();
$leads = $leadsController->index();
$dropdownData = $leadsController->getDropdownData();

// Verificar el rol del usuario
$usuarioRol = $_SESSION['user']['rol'] ?? 'usuario'; // Aseg¨²rate de que $_SESSION['user']['rol'] tenga el valor correcto
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
        <!-- Bot¨®n para agregar o editar leads dependiendo del rol -->
        <?php if ($usuarioRol === 'usuario'): ?>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addLeadModal">Agregar nuevo lead</button>
        <?php elseif ($usuarioRol === 'gerente'): ?>
            <button class="btn btn-warning" data-toggle="modal" data-target="#editLeadModal">Editar leads</button>
        <?php endif; ?>
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
                        <?php if ($usuarioRol === 'usuario'): ?>
                            <a href="viewLead.php?id=<?= $lead['id'] ?>" class="btn btn-info btn-sm">Detalle</a>
                            <a href="editLead.php?id=<?= $lead['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="deleteLead.php?id=<?= $lead['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Seguro que desea eliminar?');">Eliminar</a>
                        <?php elseif ($usuarioRol === 'gerente'): ?>
                            <?php if ($lead['estatus'] !== 'Completado'): ?>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#completeLeadModal" 
                                    onclick="fillCompleteLeadModal(<?= htmlspecialchars(json_encode($lead)) ?>)">
                                    Completar
                                </button>
                            <?php else: ?>
                                <button class="btn btn-success btn-sm" disabled>Lead Completo</button>
                            <?php endif; ?>
                        <?php endif; ?>
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
                        <!-- Campos del formulario -->
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Lead -->
<div class="modal fade" id="editLeadModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Lead</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editLeadForm" action="../controllers/leadsController.php?action=editLead" method="POST">
                    <div class="form-group">
                        <label for="editLeadSelect">Selecciona un Lead:</label>
                        <select class="form-control" id="editLeadSelect" name="lead_id">
                            <?php foreach ($leads as $lead): ?>
                                <option value="<?= $lead['id'] ?>"><?= $lead['empresa'] ?> - <?= $lead['localidad'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning btn-block">Editar Lead</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Completar Lead -->
<div class="modal fade" id="completeLeadModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Completar Lead</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="completeLeadForm" action="../controllers/leadsController.php?action=completeLead" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="completeLeadId" name="id">
                    <div class="form-group">
                        <label for="cotizacion">Cotizaci¨®n</label>
                        <input type="text" class="form-control" id="cotizacion" name="cotizacion" required>
                    </div>
                    <div class="form-group">
                        <label for="archivo">Archivo</label>
                        <input type="file" class="form-control" id="archivo" name="archivo">
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Completar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function fillCompleteLeadModal(lead) {
        document.getElementById('completeLeadId').value = lead.id;
        document.getElementById('cotizacion').value = lead.cotizacion || '';
    }

    $(document).ready(function() {
        $('#leadsTable').DataTable();
    });
</script>

<?php include('components/footer.php'); ?>
