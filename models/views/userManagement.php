<?php
$activePage = 'users';
require_once('components/header.php');
require_once('../controllers/userController.php');

// Instancia del modelo y obtención de datos
$userModel = new UserModel();
$usuarios = $userModel->getUsuarios();
$puestos = $userModel->getPuestos();
$sucursales = $userModel->getSucursales();
$roles = $userModel->getRoles();

// Validación de permisos
$rolPermitido = 1;
if ($_SESSION['user']['rol'] != $rolPermitido) {
    header("HTTP/1.1 404 Not Found");
    echo "<h1>No tienes permiso para acceder a esta página.</h1>";
    exit;
}
?>

<main class="container mt-4">
    <h1>Gestión de Usuarios</h1>
    <p>Listado y gestión de usuarios del sistema.</p>
    <!-- Contenedor de alerta para mostrar mensajes -->
    <div id="alertContainer" class="alert d-none" role="alert"></div>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Añadir Usuario</button>
        <div class="d-flex">
            <input type="text" id="userSearch" class="form-control mr-2" placeholder="Buscar usuario">
            <button class="btn btn-secondary" onclick="filterUsers()">Filtrar Usuarios</button>
        </div>
    </div>

    <!-- Tabla de usuarios responsiva -->
    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
        <table class="table table-bordered table-striped">
            <thead style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1;">
                <tr>
                    <th>Nombre de Usuario</th>
                    <th>Correo Electrónico</th>
                    <th>Puesto</th>
                    <th>Sucursal</th>
                    <th>Estado</th>
                    <th>Último Acceso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($usuarios)): ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['nombre']); ?></td>
                            <td><?= htmlspecialchars($usuario['correo']); ?></td>
                            <td><?= htmlspecialchars($usuario['puesto']); ?></td>
                            <td><?= htmlspecialchars($usuario['sucursal']); ?></td>
                            <td><?= $usuario['estado'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
                            <td><?= htmlspecialchars($usuario['ultimo_acceso'] ?? 'No registrado'); ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editUserModal"
                                    onclick="fillEditModal(
                                        <?= htmlspecialchars(json_encode($usuario)); ?>
                                    )">Editar</button>
                                <a href="javascript:void(0);" class="btn btn-sm btn-danger"
                                   onclick="confirmDeleteUser('../controllers/userController.php?action=deleteUser&id_usuarios=<?= $usuario['id_usuarios']; ?>')">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No se encontraron usuarios.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Modal para añadir un usuario -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Añadir Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" action="../controllers/userController.php?action=addUser" method="POST">
                    <div class="form-group">
                        <label for="addNombre">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="addNombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="addCorreo">Correo Electrónico</label>
                        <input type="email" class="form-control" id="addCorreo" name="correo" required>
                    </div>
                    <div class="form-group">
                        <label for="addPassword">Contraseña</label>
                        <input type="password" class="form-control" id="addPassword" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="addRol">Rol</label>
                        <select class="form-control" id="addRol" name="rol" required>
                            <option value="" selected>Seleccionar</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id_roles']; ?>"><?= $role['rol']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="addPuesto">Puesto</label>
                        <select class="form-control" id="addPuesto" name="puesto" required>
                            <option value="" selected>Seleccionar</option>
                            <?php foreach ($puestos as $puesto): ?>
                                <option value="<?= $puesto['id_puestos']; ?>"><?= $puesto['puesto']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="addSucursal">Sucursal</label>
                        <select class="form-control" id="addSucursal" name="sucursal" required>
                            <option value="" selected>Seleccionar</option>
                            <?php foreach ($sucursales as $sucursal): ?>
                                <option value="<?= $sucursal['id_sucursales']; ?>"><?= $sucursal['sucursal']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar un usuario -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" action="../controllers/userController.php?action=editUser" method="POST">
                    <input type="hidden" id="editUserId" name="id_usuarios">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editNombre">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="editNombre" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="editCorreo">Correo Electrónico</label>
                                <input type="email" class="form-control" id="editCorreo" name="correo" required>
                            </div>
                            <div class="form-group">
                                <label for="editPassword">Contraseña</label>
                                <input type="password" class="form-control" id="editPassword" name="password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editRol">Rol</label>
                                <select class="form-control" id="editRol" name="rol" required>
                                    <option value="" selected>Seleccionar</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['id_roles']; ?>"><?= $role['rol']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editPuesto">Puesto</label>
                                <select class="form-control" id="editPuesto" name="puesto" required>
                                    <option value="" selected>Seleccionar</option>
                                    <?php foreach ($puestos as $puesto): ?>
                                        <option value="<?= $puesto['id_puestos']; ?>"><?= $puesto['puesto']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editSucursal">Sucursal</label>
                                <select class="form-control" id="editSucursal" name="sucursal" required>
                                    <option value="" selected>Seleccionar</option>
                                    <?php foreach ($sucursales as $sucursal): ?>
                                        <option value="<?= $sucursal['id_sucursales']; ?>"><?= $sucursal['sucursal']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning btn-block">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('components/footer.php'); ?>
