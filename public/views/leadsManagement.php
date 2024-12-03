<?php 
$activePage = 'leadsManagement';
include('components/header.php');
require_once('../controllers/leadsController.php');

$leadsController = new LeadsController();
$dropdownData = $leadsController->getDropdownData();

// Verificar si la clave 'contacto' está definida en $dropdownData
$contactos = isset($dropdownData['contacto']) ? $dropdownData['contacto'] : [];
$estatus = isset($dropdownData['estatus']) ? $dropdownData['estatus'] : [];
$gerentes = isset($dropdownData['gerentes']) ? $dropdownData['gerentes'] : [];
$periodos = isset($dropdownData['periodos']) ? $dropdownData['periodos'] : [];
?>

<link rel="stylesheet" href="../public/css/styleleadsManagement.css">

<main class="container mt-5">
    <h1 class="text-center mb-4">Gestión de Campos de Leads</h1>
    <p class="text-center">Administre los valores de las listas desplegables para los leads.</p>

    <div class="row">
        <!-- Contenedor para Gestionar Medios de Contacto -->
        <div class="col-md-3">
            <div class="card manage-card" data-target="#manageMediosModal">
                <div class="card-body">
                    <h5 class="card-title">Medios de Contacto</h5>
                    <button class="btn btn-primary btn-sm">Gestionar</button>
                </div>
            </div>
        </div>

        <!-- Contenedor para Gestionar Estatus -->
        <div class="col-md-3">
            <div class="card manage-card" data-target="#manageEstatusModal">
                <div class="card-body">
                    <h5 class="card-title">Estatus</h5>
                    <button class="btn btn-primary btn-sm">Gestionar</button>
                </div>
            </div>
        </div>

        <!-- Contenedor para Gestionar Gerentes -->
        <div class="col-md-3">
            <div class="card manage-card" data-target="#manageGerentesModal">
                <div class="card-body">
                    <h5 class="card-title">Gerentes</h5>
                    <button class="btn btn-primary btn-sm">Gestionar</button>
                </div>
            </div>
        </div>

        <!-- Contenedor para Gestionar Periodos -->
        <div class="col-md-3">
            <div class="card manage-card" data-target="#managePeriodosModal">
                <div class="card-body">
                    <h5 class="card-title">Periodos</h5>
                    <button class="btn btn-primary btn-sm">Gestionar</button>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modals -->
<!-- Modal para Gestionar Medios de Contacto -->
<div class="modal fade" id="manageMediosModal" tabindex="-1" role="dialog" aria-labelledby="manageMediosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gestionar Medios de Contacto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Tabla de Medios de Contacto -->
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addMedioModal">Agregar Medio</button>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Medio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dropdownData['contacto'] as $contacto): ?>
                        <tr>
                            <td><?= htmlspecialchars($contacto['id_contacto']) ?></td>
                            <td><?= htmlspecialchars($contacto['contacto']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn" data-id="<?= $contacto['id_contacto'] ?>" data-name="<?= $contacto['contacto'] ?>" data-type="contacto">Editar</button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $contacto['id_contacto'] ?>" data-type="contacto">Eliminar</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Gestionar Estatus -->
<div class="modal fade" id="manageEstatusModal" tabindex="-1" role="dialog" aria-labelledby="manageEstatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gestionar Estatus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Tabla de Estatus -->
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addEstatusModal">Agregar Estatus</button>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dropdownData['estatus'] as $estatus): ?>
                        <tr>
                            <td><?= htmlspecialchars($estatus['id_estatus']) ?></td>
                            <td><?= htmlspecialchars($estatus['estatus']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn" data-id="<?= $estatus['id_estatus'] ?>" data-name="<?= $estatus['estatus'] ?>" data-type="estatus">Editar</button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $estatus['id_estatus'] ?>" data-type="estatus">Eliminar</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal para Gestionar Gerentes -->
<div class="modal fade" id="manageGerentesModal" tabindex="-1" role="dialog" aria-labelledby="manageGerentesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gestionar Gerentes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <!-- Botón para agregar un nuevo Gerente -->
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addGerenteModal">Agregar Gerente</button>
                <!-- Tabla de Gerentes -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gerente</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dropdownData['gerentes'] as $gerente): ?>
                        <tr>
                            <td><?= htmlspecialchars($gerente['id_gerente']) ?></td>
                            <td><?= htmlspecialchars($gerente['gerente']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn" data-id="<?= $gerente['id_gerente'] ?>" data-name="<?= $gerente['gerente'] ?>" data-type="gerente">Editar</button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $gerente['id_gerente'] ?>" data-type="gerente">Eliminar</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Gestionar Periodos -->
<div class="modal fade" id="managePeriodosModal" tabindex="-1" role="dialog" aria-labelledby="managePeriodosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gestionar Periodos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Botón para agregar un nuevo Periodo -->
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addPeriodoModal">Agregar Periodo</button>
                <!-- Tabla de Periodos -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Periodo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dropdownData['periodos'] as $periodo): ?>
                        <tr>
                            <td><?= htmlspecialchars($periodo['id_periodo']) ?></td>
                            <td><?= htmlspecialchars($periodo['periodo']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn" data-id="<?= $periodo['id_periodo'] ?>" data-name="<?= $periodo['periodo'] ?>" data-type="periodo">Editar</button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $periodo['id_periodo'] ?>" data-type="periodo">Eliminar</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function () {
    $(".manage-card").click(function () {
        const targetModal = $(this).data("target");
        $(targetModal).modal("show");
    });
});
</script>

<?php include('components/footer.php'); ?>
