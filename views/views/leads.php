<?php 
$activePage = 'leads';
include('components/header.php'); 
require_once('../controllers/leadsController.php');

// Instancia del controlador y carga de datos
$leadsController = new LeadsController();
$leads = $leadsController->index();
$dropdownData = $leadsController->getDropdownData();

// Verificar y depurar el rol del usuario
$usuarioRol = $_SESSION['user']['rol'] ?? null;

if (!$usuarioRol) {
    error_log("Rol de usuario no definido en la sesión.");
}
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
    <div class="role-button-container">
        <!-- Botón para agregar o editar leads dependiendo del rol -->
        <?php if ($usuarioRol === 'usuario'): ?>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addLeadModal">Agregar Nuevo Lead</button>
        <?php elseif ($usuarioRol === 'gerente'): ?>
            <button class="btn btn-warning" data-toggle="modal" data-target="#editLeadModal">Modificar Lead</button>
        <?php elseif ($usuarioRol === 'administrador'): ?>
            <div>
                <button class="btn btn-primary" data-toggle="modal" data-target="#addLeadModal">Agregar Nuevo Lead</button>
                <button class="btn btn-warning" data-toggle="modal" data-target="#editLeadModal">Modificar Lead</button>
            </div>
        <?php else: ?>
            <p class="text-danger">No tienes permisos para realizar esta acción.</p>
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
                            <a href="../Leads/<?= htmlspecialchars($lead['archivo']) ?>" target="_blank" download>Descargar Archivo</a>
                        <?php else: ?>
                            N/D
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($usuarioRol === 'usuario' || $usuarioRol === 'administrador'): ?>
                            <a href="viewLead.php?id=<?= $lead['id'] ?>" class="btn btn-info btn-sm">Detalle</a>
                            <a href="editLead.php?id=<?= $lead['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="deleteLead.php?id=<?= $lead['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que desea eliminar?');">Eliminar</a>
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

<!-- Modales -->
<div class="modal fade" id="addLeadModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <!-- Modal para añadir lead -->
</div>
<div class="modal fade" id="editLeadModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <!-- Modal para editar lead -->
</div>

<script>
    $(document).ready(function() {
        $('#leadsTable').DataTable();
    });

    function fillCompleteLeadModal(lead) {
        document.getElementById('completeLeadId').value = lead.id;
        document.getElementById('cotizacion').value = lead.cotizacion || '';
    }
</script>

<?php include('components/footer.php'); ?>
