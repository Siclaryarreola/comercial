<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Leads - Login</title>
    
    <!-- Estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="icon" href="../public/images/favico.png" type="image/x-icon">
</head>
<body class="bg-blue d-flex align-items-center justify-content-center min-vh-100">

<div class="container d-flex justify-content-center" style="max-width: 900px;">
    <div class="row shadow-lg rounded overflow-hidden w-100">
        
        <!-- Columna de imagen -->
        <div class="col-lg-6 d-none d-lg-block p-0">
            <img src="../public/images/bannerLogin.jpg" alt="Banner Image" class="img-fluid h-100 w-100" style="object-fit: cover;">
        </div>

        <!-- Columna del formulario -->
        <div class="col-lg-6 col-md-12 d-flex align-items-center bg-white">
            <div class="p-4 w-100">
                <div class="text-center mb-4">
                    <img src="../public/images/logo-d.png" alt="Company Logo" class="img-fluid" style="height: 60px;">
                </div>
                <h2 class="card-title text-center">Portal Comercial</h2>
                <p class="text-muted text-center">Bienvenido a nuestro portal. Por favor, inicia sesión para continuar.</p>

                <form id="loginForm" action="../index.php?controller=login&action=login" method="POST">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="usuario@drg.mx" required autocomplete="username">
                        <label for="email">Correo Electrónico</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="********" required autocomplete="current-password">
                        <label for="password">Contraseña</label>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="recuerdame">
                            <label class="form-check-label" for="recuerdame">Recuérdame</label>
                        </div>
                        <a href="forgotPass.php" class="text-decoration-none">Olvidé mi contraseña</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">INGRESAR</button>
                </form>

                <p class="text-center mt-3">
                    ¿No tienes una cuenta? <a href="register.php" class="text-primary">Regístrate</a>
                </p>
            </div>
        </div>

    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="../public/js/loginValidation.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Previene el envío tradicional del formulario

                const formData = new FormData(loginForm);
                fetch(loginForm.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mostrar mensaje de éxito y redirigir al dashboard
                        Swal.fire({
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = data.redirect;
                        });
                    } else {
                        // Mostrar mensaje de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            confirmButtonText: 'Aceptar'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al procesar la solicitud.',
                        confirmButtonText: 'Aceptar'
                    });
                });
            });
        }
    });
</script>

</body>
</html>
