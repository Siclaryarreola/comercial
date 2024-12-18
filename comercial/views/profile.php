<?php
$activePage = 'profile'; // Página activa
include('components/header.php'); // Incluir header
require_once('../controllers/profileController.php');

try {
    $profileController = new ProfileController();
    $profileData = $profileController->getProfileData();
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>{$e->getMessage()}</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/styleProfile.css">

</head>
<body>
    <main class="container profile-container"> <!-- Margen reducido -->
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- Foto y Detalles del Usuario -->
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <img src="<?php echo htmlspecialchars($profileData['foto_perfil'] ?? 'profile-photo.jpg'); ?>" class="card-img-top mt-3 rounded-circle" alt="Foto del perfil" style="width: 70%; margin: 0 auto;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($profileData['nombre']); ?></h5>
                                        <p class="card-text mb-2 font-weight-bold"><?php echo htmlspecialchars($profileData['puesto'] ?? 'N/A'); ?></p>
                                        <p class="text-muted">Sucursal: <?php echo htmlspecialchars($profileData['sucursal'] ?? 'N/A'); ?></p>
                                        <form action="../controllers/profileController.php?action=updatePhoto" method="POST" enctype="multipart/form-data" class="mt-3">
                                            <input type="file" name="foto_perfil" class="form-control-file">
                                            <button type="submit" class="btn btn-primary btn-sm mt-2">Editar Foto</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Formulario de Edición -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header bg-dark text-white">
                                        Editar Perfil
                                    </div>
                                    <div class="card-body">
                                        <form action="../controllers/profileController.php?action=updateProfile" method="POST">
                                            <div class="form-group row">
                                                <label for="nombre" class="col-sm-3 col-form-label">Nombre</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($profileData['nombre']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="correo" class="col-sm-3 col-form-label">Correo Electrónico</label>
                                                <div class="col-sm-9">
                                                    <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($profileData['correo']); ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="telefono" class="col-sm-3 col-form-label">Teléfono</label>
                                                <div class="col-sm-9">
                                                    <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Ej. +52 123 456 7890" value="+52 123 456 7890">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="contraActual" class="col-sm-3 col-form-label">Contraseña Actual</label>
                                                <div class="col-sm-9">
                                                    <input type="password" class="form-control" id="contraActual" name="currentPassword">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="nuevaContra" class="col-sm-3 col-form-label">Nueva Contraseña</label>
                                                <div class="col-sm-9">
                                                    <input type="password" class="form-control" id="nuevaContra" name="newPassword">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="confirmarContra" class="col-sm-3 col-form-label">Confirmar Contraseña</label>
                                                <div class="col-sm-9">
                                                    <input type="password" class="form-control" id="confirmarContra" name="confirmPassword">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-9 offset-sm-3 text-right">
                                                    <button type="submit" class="btn btn-primary">Actualizar Información</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Formulario -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
<?php include('components/footer.php'); ?>
