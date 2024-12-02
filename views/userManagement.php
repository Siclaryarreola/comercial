<?php
$activePage = 'users';
require_once('components/header.php');
require_once ('../controllers/userController.php');

// Instancia del modelo y obtención de datos
$userModel = new userModel();
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

    <table class="table table-bordered table-striped">
        <thead>
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
                               onclick="confirmDeleteUser('../controllers/userController.php?action=deleteUser&id=<?= $usuario['id']; ?>')">
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
</main>

<?php include('components/footer.php'); ?>
