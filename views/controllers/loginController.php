<?php
require_once('models/loginModel.php');
require_once('controllers/sessionManager.php');

class loginController {
    private $loginModel;

    public function __construct() 
    {
        $this->loginModel = new loginModel();
    }

    public function showLoginForm() 
    {
        require('views/login.php');
    }

    public function showForgotForm()
    {
        require('views/forgotPass.php');
    }

    public function login() 
    {
        header('Content-Type: application/json'); // Configurar el encabezado para JSON
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email']) && !empty($_POST['password'])) {
            $email = strtolower(trim($_POST['email']));
            $password = $_POST['password'];

            // Obtener usuario del modelo
            $user = $this->loginModel->getUserByEmailAndPassword($email, $password);

            if (is_array($user)) 
            {
                // Inicio de sesión exitoso
                SessionManager::createSession($user);
                echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso', 'redirect' => '../views/dashboard.php']);
                exit;
            } 
            else if ($user === 'blocked') 
            {
                // Usuario bloqueado
                error_log("Cuenta bloqueada para el usuario: {$email}");
                echo json_encode(['success' => false, 'message' => 'La cuenta está bloqueada temporalmente. Intente de nuevo más tarde.']);
                exit;
            } 
            else if ($user === 'incorrect_password') 
            {
                // Contraseña incorrecta
                error_log("Contraseña incorrecta para el usuario: {$email}");
                echo json_encode(['success' => false, 'message' => 'La contraseña ingresada es incorrecta.']);
                exit;
            } 
            else 
            {
                // Usuario no encontrado
                error_log("Usuario no encontrado: {$email}");
                echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
                exit;
            }
        } 
        else 
        {
            // Error en la solicitud (faltan datos)
            echo json_encode(['success' => false, 'message' => 'Correo y contraseña son obligatorios.']);
            exit;
        }
    }
}
?>
