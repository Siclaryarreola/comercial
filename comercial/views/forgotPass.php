<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="icon" href="../public/images/favico.png" type="image/x-icon">
    <link rel="shortcut icon" href="../public/images/favico.png" type="image/x-icon">
</head>
<body class="bg-white">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <img src="../public/images/logo-d.png" alt="Company Logo" style="height: 60px;">
                </div>
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center">Restablecer Contraseña</h2>
                        <p class="text-muted text-center">Ingresa tu correo electrónico para recibir las instrucciones para restablecer tu contraseña.</p>

                        <form id="forgotPasswordForm" action="../index.php?controller=user&action=resetPassword" method="POST">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="usuario@drg.mx" required>
                                <label for="email">Correo Electrónico</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Enviar Instrucciones</button>
                        </form>

                        <p class="text-center mt-3">
                            <a href="login.php" class="text-primary">Regresar al login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="public/js/loginValidation.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>