<?php
$activePage = 'users';
require_once('components/header.php');
require_once('../controllers/userController.php');

$controller = new UserController();
$data = $controller->getUsersData();

$usuarios = $data['usuarios'];
$puestos = array_column($data['puestos'], 'puesto', 'id_puestos');
$sucursales = array_column($data['sucursales'], 'sucursal', 'id_sucursales');
$roles = array_column($data['roles'], 'rol', 'id_roles');

// Validación de permisos
$rolPermitido = 1;
if ($_SESSION['user']['rol'] != $rolPermitido) {
    header("HTTP/1.1 404 Not Found");
    echo "<h1>No tienes permiso para acceder a esta página.</h1>";
    exit;
}
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main class="container mt-4">
    <div class="text-center py-3 mb-4">
        <h1 style="font-weight: 600;">Gestión de Usuarios</h1>
        <p class="text-muted">Listado y gestión de usuarios del sistema.</p>
    </div>
    
    <div class="text-left mb-2">
        <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#addUserModal" style="background-color: #0d850d; border-color: #0d850d;">Añadir Usuario</button>
    </div>
    
    <div class="table-responsive mb-5">
        <table class="table table-bordered table-striped" id="userTable">
            <thead>
                <tr>
                    <th>Nombre de Usuario</th>
                    <th>Correo Electrónico</th>
                    <th>Puesto</th>
                    <th>Sucursal</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Último Acceso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
               <tr data-id="<?= $usuario['id_usuarios']; ?>">
                <td contenteditable="true"><?= htmlspecialchars($usuario['nombre']); ?></td>
                <td contenteditable="true"><?= htmlspecialchars($usuario['correo']); ?></td>
                <td class="editable-select" data-type="puesto" data-value="<?= $usuario['puesto']; ?>" data-options='<?= htmlspecialchars(json_encode($puestos), ENT_QUOTES, 'UTF-8'); ?>'>
                    <?= htmlspecialchars($puestos[$usuario['puesto']]); ?>
                </td>
                <td class="editable-select" data-type="sucursal" data-value="<?= $usuario['sucursal']; ?>" data-options='<?= htmlspecialchars(json_encode($sucursales), ENT_QUOTES, 'UTF-8'); ?>'>
                    <?= htmlspecialchars($sucursales[$usuario['sucursal']]); ?>
                </td>
                <td class="editable-select" data-type="rol" data-value="<?= $usuario['rol']; ?>" data-options='<?= htmlspecialchars(json_encode($roles), ENT_QUOTES, 'UTF-8'); ?>'>
                    <?= htmlspecialchars($roles[$usuario['rol']]); ?>
                </td>
                <td>
                    <select class="form-control">
                        <option value="Activo" <?= 'Activo' == $usuario['estado'] ? 'selected' : ''; ?>>Activo</option>
                        <option value="Inactivo" <?= 'Inactivo' == $usuario['estado'] ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </td>
                <td><?= htmlspecialchars($usuario['ultimo_acceso'] ?? 'No registrado'); ?></td>
                <td>
                    <button class="btn btn-success" style="background-color: #33a633; color: white;" onclick="editUser(<?= $usuario['id_usuarios']; ?>)" disabled>Guardar</button>
                    <button class="btn btn-danger" onclick="confirmDeleteUser(<?= $usuario['id_usuarios']; ?>)">Eliminar</button>
                </td>
            </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
   <!-- Modal para añadir un usuario -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="addUserModalLabel">Añadir Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <form id="addUserForm" method="POST" onsubmit="addUser(event)">
                    <div class="form-group">
                        <label for="nombre">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="puesto">Puesto</label>
                        <select class="form-control" id="puesto" name="puesto" required>
                            <?php foreach ($puestos as $id => $puesto): ?>
                                <option value="<?= $id ?>"><?= $puesto ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sucursal">Sucursal</label>
                        <select class="form-control" id="sucursal" name="sucursal" required">
                            <?php foreach ($sucursales as $id => $sucursal): ?>
                                <option value="<?= $id ?>"><?= $sucursal ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="rol">Rol</label>
                        <select class="form-control" id="rol" name="rol" required>
                            <?php foreach ($roles as $id => $rol): ?>
                                <option value="<?= $id ?>"><?= $rol ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" style="background-color: #33a633; border-color: #33a633;">Guardar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</main>

<script src="../public/js/userManagementTable.js"></script>

<?php include('components/footer.php'); ?>