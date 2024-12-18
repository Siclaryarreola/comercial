<?php
require_once('controllers/loginController.php');
require_once('controllers/registerController.php');
require_once('controllers/sessionManager.php');

SessionManager::initSession();

define('TIEMPO_MAXIMO_INACTIVIDAD', 180); // 30 minutos
if (isset($_SESSION['ultimo_tiempo_actividad']) && (time() - $_SESSION['ultimo_tiempo_actividad'] > TIEMPO_MAXIMO_INACTIVIDAD)) {
    include('controllers/logout.php');
    exit;
}

$_SESSION['ultimo_tiempo_actividad'] = time();

$action = $_GET['action'] ?? 'showLoginForm'; // Define accion por defecto
$controllerName = $_GET['controller'] ?? 'login'; // Controlador por defecto

switch ($controllerName) 
{
    case 'login':
        $controller = new loginController();
        break;
    case 'register':
        $controller = new registerController();
        break;
    default:
        header("HTTP/1.0 404 Not Found");
        echo "Page not found.";
        exit;
}

if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    header("HTTP/1.0 404 Not Found");
    echo "Action not found.";
    exit;
}

if (isset($_SESSION['success'])) {
    echo "<script>sessionStorage.setItem('successMessage', '" . $_SESSION['success'] . "');</script>";
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo "<script>sessionStorage.setItem('errorMessage', '" . $_SESSION['error'] . "');</script>";
    unset($_SESSION['error']);
}