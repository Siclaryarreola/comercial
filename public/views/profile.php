<?php
$activePage = 'profile';
include('components/header.php');
require_once('../controllers/profileController.php');

$profileController = new ProfileController();
$profileData = $profileController->getProfileData();

?>
<link rel="stylesheet" href="../public/css/styleProfile.css">

<main class="container mt-4">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="profileTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Perfil</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#password" role="tab" aria-controls="profile" aria-selected="false">Cambiar Contraseña</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row align-items-center">
                <div class="col-md-4 text-center">
                    <img src="<?php echo htmlspecialchars($profileData['foto_perfil'] ?? '../photos/images_usuario.png'); ?>" alt="Foto de perfil" class="img-fluid rounded-circle shadow-sm">
                    <form action="../controllers/profileController.php?action=updatePhoto" method="POST" enctype="multipart/form-data" class="mt-3">
                        <input type="file" name="foto_perfil" class="form-control-file">
                        <button type="submit" class="btn btn-primary mt-2">Cambiar Foto</button>
                    </form>
                </div>
                <div class="col-md-8">
                    <h2>Información de Perfil</h2>
                    <form>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" value="<?php echo htmlspecialchars($profileData['nombre'] ?? ''); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" value="<?php echo htmlspecialchars($profileData['correo'] ?? ''); ?>" readonly>
                        </div>
                        <!-- Otros campos como puesto y sucursal si están disponibles -->
                    </form>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="profile-tab">
            <h2>Cambiar Contraseña</h2>
            <form action="profileController.php?action=changePassword" method="POST">
                <div class="form-group">
                    <label for="currentPassword">Contraseña Actual</label>
                    <input type="password" class="form-control" id="currentPassword" name="currentPassword" autocomplete="current-password" required>
                </div>
                <div class="form-group">
                    <label for="newPassword">Nueva Contraseña</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" autocomplete="new-password" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirmar Nueva Contraseña</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" autocomplete="new-password" required>
                </div>
                <button type="submit" class="btn btn-success">Actualizar Contraseña</button>
            </form>
        </div>
    </div>
</main>

<?php include('components/footer.php'); ?>