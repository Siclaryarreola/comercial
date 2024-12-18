<?php 
require_once('../controllers/sessionManager.php');

// Inicializar sesión y autenticar usuario
SessionManager::initSession();
SessionManager::authenticate();

$user = $_SESSION['user'] ?? null;

// Verificar si el usuario está autenticado y tiene un rol válido
if (!$user || !isset($user['rol'])) {
    error_log("Error: Usuario no autenticado o sin rol definido.");
    header('Location: ../index.php?controller=login&action=showLoginForm');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Leads</title>

    <!-- Bootstrap 4 desde CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="../public/css/styleDashboard.css">
    <link rel="icon" href="../public/images/favico.png" type="image/x-icon">
    <link rel="shortcut icon" href="../public/images/favico.png" type="image/x-icon">
</head>
<body>
<header class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="../views/dashboard.php">
            <img src="../public/images/logo-d.png" alt="Company Logo" style="height: 50px;">
        </a>
        <!-- Botón hamburguesa para dispositivos móviles -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto">
                
                            <!-- Todos los roles tienen acceso a Inicio -->
            <li class="nav-item <?php echo ($activePage == 'dashboard') ? 'active' : ''; ?>">
                <a class="nav-link" href="../views/dashboard.php">Inicio</a>
            </li>
            
            
            <!-- Roles específicos para Generador de Demanda (rol 0) -->
            <?php if ($user['rol'] === 0): ?>
                <li class="nav-item <?php echo ($activePage == 'leads') ? 'active' : ''; ?>">
                    <a class="nav-link" href="../views/leads.php">Leads</a>
                </li>
            <?php endif; ?>
            
            <!-- Roles específicos para Administrador (rol 1) -->
            <?php if ($user['rol'] === 1): ?>
                <li class="nav-item <?php echo ($activePage == 'users') ? 'active' : ''; ?>">
                    <a class="nav-link" href="../views/userManagement.php">Usuarios</a>
                </li>
                <li class="nav-item <?php echo ($activePage == 'leads') ? 'active' : ''; ?>">
                    <a class="nav-link" href="../views/leads.php">Leads</a>
                </li>
                <li class="nav-item <?php echo ($activePage == 'leadsManagement') ? 'active' : ''; ?>">
                    <a class="nav-link" href="../views/leadsManagement.php">Gestión de Leads</a>
                </li>
                 <!-- Todos los roles tienen acceso al Buzón de Leads -->
                <li class="nav-item <?php echo ($activePage == 'buzonLeads') ? 'active' : ''; ?>">
                    <a class="nav-link" href="../views/buzonLeads.php">Buzón Leads</a>
                </li>
                <li class="nav-item <?php echo ($activePage == 'graphics') ? 'active' : ''; ?>">
                    <a class="nav-link" href="../views/graphics.php">Desempeño de Leads</a>
                </li>
            <?php endif; ?>
            
            <!-- Roles específicos para Gerente Comercial (rol 2) -->
            <?php if ($user['rol'] === 2): ?>
                <li class="nav-item <?php echo ($activePage == 'leads') ? 'active' : ''; ?>">
                    <a class="nav-link" href="../views/leads.php">Leads</a>
                </li>
                <li class="nav-item <?php echo ($activePage == 'leadsManagement') ? 'active' : ''; ?>">
                    <a class="nav-link" href="../views/leadsManagement.php">Gestión de Leads</a>
                </li>
                 <!-- Todos los roles tienen acceso al Buzón de Leads -->
                <li class="nav-item <?php echo ($activePage == 'buzonLeads') ? 'active' : ''; ?>">
                    <a class="nav-link" href="../views/buzonLeads.php">Buzón Leads</a>
                </li>
               <li class="nav-item <?php echo ($activePage == 'graphics') ? 'active' : ''; ?>">
                    <a class="nav-link" href="../views/graphics.php">Desempeño de Leads</a>
                </li>
            <?php endif; ?>
            
            <!-- Roles específicos para Gerente de Sucursal (rol 3) -->
            <?php if ($user['rol'] === 3): ?>
                <li class="nav-item <?php echo ($activePage == 'buzonLeads') ? 'active' : ''; ?>">
                    <a class="nav-link" href="../views/buzonLeads.php">Buzón Leads</a>
                </li>
            <?php endif; ?>
                            
                 
                
                

                <!-- Menú desplegable para todos los roles -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Bienvenido, <?php echo htmlspecialchars($user['nombre']); ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="../views/profile.php">Mi Perfil</a>
                        <a class="dropdown-item" href="../controllers/logOut.php" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Sesión</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>

<!-- Formulario oculto para cerrar sesión -->
<form id="logout-form" action="../controllers/logOutController.php" method="POST" style="display:none;"></form>

<!-- Scripts de Bootstrap 4 -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
