<?php
// Obtiene el token desde la URL
$token = $_GET['token'] ?? null;

// Verifica si el token está presente
if (!$token) {
    echo "<p class='text-center text-danger'>Error: Token no válido o ausente.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh; background-color: #f0f2f5;">
<main class="form-container mx-auto" style="background-color: white; padding: 30px; border-radius: 8px; max-width: 400px;">
    <h1 class="text-center">Restablecer Contraseña</h1>
    <form action="index.php?controller=auth&action=updatePassword" method="POST">
        <!-- Campo oculto para enviar el token -->
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <div class="form-floating mb-3">
            <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Nueva Contraseña" required>
            <label for="new_password">Nueva Contraseña</label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirmar Contraseña" required>
            <label for="confirm_password">Confirmar Contraseña</label>
        </div>
        <button type="submit" class="btn btn-primary w-100">Restablecer Contraseña</button>
    </form>
</main>
</body>
</html>
