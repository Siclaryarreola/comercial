<?php 
$activePage = 'leadsManagement';
include('components/header.php');

require_once('../controllers/leadsManagementController.php');

// Instancia el controlador
$leadsManagementController = new LeadsManagementController();

// Obtén los datos necesarios
$dropdownData = $leadsManagementController->getDropdownData();

$contactos   = $dropdownData['contactos'] ?? [];
$estatus     = $dropdownData['estatus'] ?? [];
$gerentes    = $dropdownData['gerentes'] ?? [];
$periodos    = $dropdownData['periodos'] ?? [];
$negocios    = $dropdownData['negocios'] ?? []; 
$leads = $dropdownData['leads'] ?? [];
$sucursales = $dropdownData['sucursales'] ?? [];

?>

<link rel="stylesheet" href="../public/css/styleleadsManagement.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


<main class="container-fluid mt-5">
    <!-- Encabezado principal -->
    <div class="text-center mb-4">
        <h1>Gestión de Campos de Leads</h1>
        <p class="lead text-muted">Ajusta los valores clave y administra los leads fácilmente.</p>
    </div>

    <div class="row">
        <!-- Contenedor Izquierdo -->
        <div class="col-md-6 d-flex flex-wrap justify-content-start">
            <!-- Grupo 1 -->
            
                        <!-- Tarjeta para Medios de contacto -->

            <div class="card m-2 shadow-sm" style="width: 12rem;" data-toggle="modal" data-target="#manageMediosModal">
                <div class="card-body text-center">
                    <i class="fas fa-headset fa-lg text-primary mb-2"></i>
                    <h6>Medios de Contacto</h6>
                    <button class="btn btn-outline-primary btn-sm">Gestionar</button>
                </div>
            </div>
            
                        <!-- Tarjeta para Estatus -->

            <div class="card m-2 shadow-sm" style="width: 12rem;" data-toggle="modal" data-target="#manageEstatusModal">
                <div class="card-body text-center">
                    <i class="fas fa-flag-checkered fa-lg text-success mb-2"></i>
                    <h6>Estatus</h6>
                    <button class="btn btn-outline-success btn-sm">Gestionar</button>
                </div>
            </div>
            
                        <!-- Tarjeta para Gerentes -->

            <div class="card m-2 shadow-sm" style="width: 12rem;" data-toggle="modal" data-target="#manageGerentesModal">
                <div class="card-body text-center">
                    <i class="fas fa-user-tie fa-lg text-warning mb-2"></i>
                    <h6>Gerentes</h6>
                    <button class="btn btn-outline-warning btn-sm">Gestionar</button>
                </div>
            </div>

            <!-- Grupo 2 -->
            <div class="card m-2 shadow-sm" style="width: 12rem;" data-toggle="modal" data-target="#managePeriodosModal">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-lg text-info mb-2"></i>
                    <h6>Periodos</h6>
                    <button class="btn btn-outline-info btn-sm">Gestionar</button>
                </div>
            </div>
            
            <!-- Tarjeta: Modelos de Negocio -->
            <div class="card m-2 shadow-sm" style="width: 12rem;" data-toggle="modal" data-target="#manageModelosNegocioModal">
                <div class="card-body text-center">
                    <i class="fas fa-briefcase fa-lg text-secondary mb-2"></i>
                    <h6>Modelos de Negocio</h6>
                    <button class="btn btn-outline-secondary btn-sm">Gestionar</button>
                </div>
            </div>

            
            <!-- Tarjeta para sucursales -->
            <div class="card m-2 shadow-sm" style="width: 12rem;" data-toggle="modal" data-target="#manageSucursalesModal">
                <div class="card-body text-center">
                    <i class="fas fa-building fa-lg text-secondary mb-2"></i>
                    <h6>Sucursales</h6>
                    <button class="btn btn-outline-secondary btn-sm">Gestionar</button>
                </div>
            </div>
            
            <!-- Tarjeta: Gerente & Sucursal -->
            <div class="card m-2 shadow-sm" style="width: 12rem;" data-toggle="modal" data-target="#manageGerenteSucursalModal">
                <div class="card-body text-center">
                    <i class="fas fa-user-cog fa-lg text-secondary mb-2"></i>
                    <h6>Reasignar Leads</h6>
                    <button class="btn btn-outline-secondary btn-sm">Gestionar</button>
                </div>
            </div>

            
                        <!-- Tarjeta para historial -->

            <div class="card m-2 shadow-sm" style="width: 12rem;" data-toggle="modal" data-target="#manageHistorialModal">
                <div class="card-body text-center">
                    <i class="fas fa-history fa-lg text-dark mb-2"></i>
                    <h6>Historial</h6>
                    <button class="btn btn-outline-dark btn-sm">Ver</button>
                </div>
            </div>
        </div>
        
        

        <!-- Contenedor Derecho -->
        <div class="col-md-6 d-flex flex-wrap justify-content-end">
            
            <!-- Tarjeta: Monto Cotizado -->
            <div class="card m-3 shadow-sm" style="width: 12rem;" data-toggle="modal" data-target="#manageMontoModal">
                <div class="card-body text-center">
                    <i class="fas fa-money-bill-wave fa-lg text-success mb-2"></i>
                    <h6>Monto Cotizado</h6>
                    <button class="btn btn-outline-success btn-sm">Gestionar</button>
                </div>
            </div>
            
            <!-- Tarjeta: Tamaño del Proyecto -->
            <div class="card m-3 shadow-sm" style="width: 12rem;" data-toggle="modal" data-target="#manageTamanioModal">
                <div class="card-body text-center">
                    <i class="fas fa-project-diagram fa-lg text-info mb-2"></i>
                    <h6>Tamaño del Proyecto</h6>
                    <button class="btn btn-outline-info btn-sm">Gestionar</button>
                </div>
            </div>
            
            <!-- Tarjeta: Tipo de Contacto -->
            <div class="card m-3 shadow-sm" style="width: 12rem;" data-toggle="modal" data-target="#manageTipoContactoModal">
                <div class="card-body text-center">
                    <i class="fas fa-user-friends fa-lg text-warning mb-2"></i>
                    <h6>Tipo de Contacto</h6>
                    <button class="btn btn-outline-warning btn-sm">Gestionar</button>
                </div>
            </div>
            
            <!-- Tarjeta: Línea de Negocio -->
            <div class="card m-3 shadow-sm" style="width: 12rem;" data-toggle="modal" data-target="#manageLineaModal">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-lg text-secondary mb-2"></i>
                    <h6>Línea de Negocio</h6>
                    <button class="btn btn-outline-secondary btn-sm">Gestionar</button>
                </div>
            </div>
        </div>
    </div>
</main>




<!-- Modals -->

<!-- Modal para Medios de Contacto -->
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
            <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addMediosModal">Agregar Medio</button>
                <?php if (!empty($contactos)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Medio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contactos as $contacto): ?>
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
                <?php else: ?>
                    <p class="text-warning">No hay datos disponibles para Medios de Contacto.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Estatus -->
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
            <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addEstatusModal">Agregar Medio</button>
                <?php if (!empty($estatus)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($estatus as $est): ?>
                            <tr>
                                <td><?= htmlspecialchars($est['id_estatus']) ?></td>
                                <td><?= htmlspecialchars($est['estatus']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?= $est['id_estatus'] ?>" data-name="<?= $est['estatus'] ?>" data-type="estatus">Editar</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $est['id_estatus'] ?>" data-type="estatus">Eliminar</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-warning">No hay datos disponibles para Estatus.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Gerentes -->
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
            <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addGerentesModal">Agregar Medio</button>
                <?php if (!empty($gerentes)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Gerente</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($gerentes as $g): ?>
                            <tr>
                                <td><?= htmlspecialchars($g['id_gerente']) ?></td>
                                <td><?= htmlspecialchars($g['gerente']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?= $g['id_gerente'] ?>" data-name="<?= $g['gerente'] ?>" data-type="gerente">Editar</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $g['id_gerente'] ?>" data-type="gerente">Eliminar</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-warning">No hay datos disponibles para Gerentes.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Periodos -->
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
            <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addPeriodosModal">Agregar Medio</button>
                <?php if (!empty($periodos)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Periodo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($periodos as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['id_periodo']) ?></td>
                                <td><?= htmlspecialchars($p['periodo']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?= $p['id_periodo'] ?>" data-name="<?= $p['periodo'] ?>" data-type="periodo">Editar</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $p['id_periodo'] ?>" data-type="periodo">Eliminar</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-warning">No hay datos disponibles para Periodos.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>




<!-- Modal para Modelos de Negocio -->
<div class="modal fade" id="manageModelosNegocioModal" tabindex="-1" role="dialog" aria-labelledby="manageNegociosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gestionar Modelos de Negocio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addNegociosModal">Agregar Medio</button>
                <?php if (!empty($negocios)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Modelo de Negocio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($negocios as $negocio): ?>
                            <tr>
                                <td><?= htmlspecialchars($negocio['id_negocio']) ?></td>
                                <td><?= htmlspecialchars($negocio['negocio']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?= $negocio['id_negocio'] ?>" data-name="<?= $negocio['negocio'] ?>" data-type="negocio">Editar</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $negocio['id_negocio'] ?>" data-type="negocio">Eliminar</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-warning">No hay modelos de negocio disponibles.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>




<!-- Modal para Sucursales -->
<div class="modal fade" id="manageSucursalesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gestionar Sucursales</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Botón para agregar una nueva sucursal -->
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addSucursalModal">Agregar Sucursal</button>
                
                <!-- Tabla dinámica con datos -->
                <?php if (!empty($sucursales)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sucursal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sucursales as $sucursal): ?>
                                <tr>
                                    <td><?= htmlspecialchars($sucursal['id_sucursales']) ?></td>
                                    <td><?= htmlspecialchars($sucursal['sucursal']) ?></td>
                                    <td>
                                        <!-- Botón Editar -->
                                        <button class="btn btn-warning btn-sm edit-btn" 
                                                data-toggle="modal" 
                                                data-target="#editSucursalModal"
                                                data-id="<?= $sucursal['id_sucursales'] ?>" 
                                                data-name="<?= htmlspecialchars($sucursal['sucursal']) ?>">
                                            Editar
                                        </button>
                                        <!-- Botón Eliminar -->
                                        <form method="post" action="../controllers/leadsManagementController.php?action=delete&table=sucursales" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= $sucursal['id_sucursales'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-warning">No hay sucursales disponibles.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<!-- Modal para Reasignar un Gerente y sucursal -->
<div class="modal fade" id="manageGerenteSucursalModal" tabindex="-1" role="dialog" aria-labelledby="manageGerenteSucursalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xxl" role="document" style="max-width: 95%;"> <!-- Aumenta el ancho -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageGerenteSucursalModalLabel">Reasignar Gerente y Sucursal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if (!empty($leads)): ?>
                    <table class="table table-striped table-responsive-lg">
                        <thead>
                            <tr>
                                
                                <th style="width: 20%;">Nombre del Cliente</th>
                                <th style="width: 20%;">Modelo de Negocio</th>
                                <th style="width: 25%;">Gerente</th>
                                <th style="width: 25%;">Sucursal</th>
                                <th style="width: 16%;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($leads as $lead): ?>
                                <tr>
                                    <form method="post" action="guardarGerenteSucursal.php">
                                        
                                       <td><?= htmlspecialchars($lead['nombre_cliente'] ?? 'Nombre no disponible') ?></td>
                                        <td><?= htmlspecialchars($lead['linea_negocio'] ?? 'Modelo no disponible') ?></td>
                                        <td>
                                            <select name="gerente_id" class="form-control w-100">
                                                <?php foreach ($gerentes as $g): ?>
                                                    <option value="<?= htmlspecialchars($g['id_gerente']) ?>" 
                                                        <?= ($g['id_gerente'] == $lead['gerente_responsable']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($g['gerente']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="sucursal_id" class="form-control w-100">
                                                <?php foreach ($sucursales as $s): ?>
                                                    <option value="<?= htmlspecialchars($s['id_sucursales']) ?>" 
                                                        <?= ($s['id_sucursales'] == $lead['id_sucursal']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($s['sucursal']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="hidden" name="lead_id" value="<?= htmlspecialchars($lead['id']) ?>">
                                            <button type="submit" class="btn btn-success btn-sm">Reasigna</button>
                                        </td>
                                    </form>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-warning">No hay leads disponibles.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<!-- Modal para Historial de cambios -->
<div class="modal fade" id="manageHistorialModal" tabindex="-1" role="dialog" aria-labelledby="manageHistorialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Historial de Cambios</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>  
            </div>
            <div class="modal-body">
                <?php if (!empty($historialCambios)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>ID Lead</th>
                                <th>Campo</th>
                                <th>Valor Anterior</th>
                                <th>Valor Nuevo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historialCambios as $h): ?>
                            <tr>
                                <td><?= htmlspecialchars($h['fecha']) ?></td>
                                <td><?= htmlspecialchars($h['lead_id']) ?></td>
                                <td><?= htmlspecialchars($h['campo']) ?></td>
                                <td><?= htmlspecialchars($h['valor_anterior']) ?></td>
                                <td><?= htmlspecialchars($h['valor_nuevo']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-warning text-center">No hay registros en el historial.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<!-- Modal de Acciones Agregar -->

<!-- Modal para Sucursales -->
<div class="modal fade" id="manageSucursalesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gestionar Sucursales</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Botón para agregar una nueva sucursal -->
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addSucursalModal">Agregar Sucursal</button>
                
                <!-- Tabla dinámica con datos desde el controlador -->
                <?php if (!empty($sucursales)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sucursal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sucursales as $sucursal): ?>
                                <tr>
                                    <td><?= htmlspecialchars($sucursal['id_sucursales']) ?></td>
                                    <td><?= htmlspecialchars($sucursal['sucursal']) ?></td>
                                    <td>
                                        <!-- Botón de Editar -->
                                        <button class="btn btn-warning btn-sm edit-sucursal" 
                                                data-id="<?= $sucursal['id_sucursales'] ?>" 
                                                data-name="<?= htmlspecialchars($sucursal['sucursal']) ?>">
                                            Editar
                                        </button>
                                        <!-- Botón de Eliminar -->
                                        <form method="post" action="../controllers/leadsManagementController.php?action=delete&table=sucursales&id=<?= $sucursal['id_sucursales'] ?>" style="display: inline;">
                                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-warning">No hay sucursales disponibles en este momento.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>



<!-- Modal para Agregar Medios de Contacto -->

<div class="modal fade" id="addMediosModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Medio de Contacto</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post" action="../controllers/leadsManagementController.php?action=add&table=contactoleads">
                    <div class="form-group">
                        <label for="medioName">Nombre del Medio</label>
                        <input type="text" class="form-control" id="medioName" name="contacto" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Modal para Agregar Sucursal -->
<div class="modal fade" id="addSucursalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Sucursal</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post" action="../controllers/leadsManagementController.php?action=add&table=sucursales">
                    <div class="form-group">
                        <label for="sucursalName">Nombre de la Sucursal</label>
                        <input type="text" class="form-control" id="sucursalName" name="sucursal" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Agregar Estatus -->

<div class="modal fade" id="addEstatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Estatus</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post" action="../controllers/leadsManagementController.php?action=add&table=estatusleads">
                    <div class="form-group">
                        <label for="estatusName">Nombre del Estatus</label>
                        <input type="text" class="form-control" id="estatusName" name="estatus" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Agregar Gerentes -->
<div class="modal fade" id="addGerentesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Gerente</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post" action="../controllers/leadsManagementController.php?action=add&table=gerentesleads">
                    <div class="form-group">
                        <label for="gerenteName">Nombre del Gerente</label>
                        <input type="text" class="form-control" id="gerenteName" name="gerente" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Agregar Periodos -->
<div class="modal fade" id="addPeriodosModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Periodo</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post" action="../controllers/leadsManagementController.php?action=add&table=periodosleads">
                    <div class="form-group">
                        <label for="periodoName">Nombre del Periodo</label>
                        <input type="text" class="form-control" id="periodoName" name="periodo" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Agregar Modelos de Negocio -->
<div class="modal fade" id="addNegociosModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Modelo de Negocio</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post" action="../controllers/leadsManagementController.php?action=add&table=negocioleads">
                    <div class="form-group">
                        <label for="negocioName">Nombre del Modelo</label>
                        <input type="text" class="form-control" id="negocioName" name="negocio" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Agregar o Editar Gerente o sucursal -->
<div class="modal fade" id="manageGerenteSucursalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reasignar Lead</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post" action="../controllers/leadsManagementController.php?action=update&table=leads">
                    <div class="form-group">
                        <label for="gerenteSelect">Gerente</label>
                        <select class="form-control" id="gerenteSelect" name="gerente_id" required>
                            <?php foreach ($gerentes as $gerente): ?>
                                <option value="<?= $gerente['id_gerente'] ?>"><?= htmlspecialchars($gerente['gerente']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sucursalSelect">Sucursal</label>
                        <select class="form-control" id="sucursalSelect" name="sucursal_id" required>
                            <?php foreach ($sucursales as $sucursal): ?>
                                <option value="<?= $sucursal['id_sucursales'] ?>"><?= htmlspecialchars($sucursal['sucursal']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Modal de Acciones Editar -->

<!-- Modal para Editar Medios de Contacto -->

<!-- Modal para Editar Medio de Contacto -->
<div class="modal fade" id="editMedioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Medio de Contacto</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post" action="../controllers/leadsManagementController.php?action=update&table=contactoleads">
                    <input type="hidden" id="editMedioId" name="id_contacto">
                    <div class="form-group">
                        <label for="editMedioName">Nombre del Medio</label>
                        <input type="text" class="form-control" id="editMedioName" name="contacto" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Modal para Editar Estatus -->

<div class="modal fade" id="editEstatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Estatus</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post" action="../controllers/leadsManagementController.php?action=update&table=estatusleads">
                    <input type="hidden" id="estatusId" name="id_estatus">
                    <div class="form-group">
                        <label for="editEstatusName">Nombre del Estatus</label>
                        <input type="text" class="form-control" id="editEstatusName" name="estatus" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Medios de Contacto -->
<!-- Modal para Editar Medios de Contacto -->
<!-- Modal para Editar Medios de Contacto -->
<!-- Modal para Editar Medios de Contacto -->



<!-- Estilo adicional opcional para mayor personalización -->
<style>
    select {
        max-width: 100%; /* Asegura que el select se expanda al máximo ancho disponible */
    }
</style>

<script>
$(document).ready(function () {
    $(".card[data-target]").click(function () {
        const targetModal = $(this).data("target");
        $(targetModal).modal("show");
    });
});
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="path/to/modalsHandler.js"></script>



<?php include('components/footer.php'); ?>
