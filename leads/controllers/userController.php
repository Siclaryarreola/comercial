<?php
require_once('../models/userModel.php'); // Ruta absoluta

class UserController 
{
    private $userModel;

    public function __construct() 
    {
        $this->userModel = new UserModel();
        if (!class_exists('UserModel')) {
            die('Error: Clase UserModel no encontrada.');
        }
    }

    public function handleRequest() 
    {
        $action = $_GET['action'] ?? '';

        switch ($action) {
            case 'addUser':
                $this->addUser();
                break;

            case 'editUser':
                $this->editUser();
                break;

            case 'deleteUser':
                $this->deleteUser();
                break;

            case 'getRoles': 
                $this->getRoles();
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Acción no válida']);
                break;
        }
    }

    private function addUser() 
    {
        $name = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? ''; 
        $role = $_POST['rol'] ?? null;
        $position = $_POST['puesto'] ?? 0;
        $branch = $_POST['sucursal'] ?? 0;

        // Validaciones
        if (empty($name) || empty($email) || empty($password) || !isset($role) || !isset($position) || !isset($branch)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido']);
            return;
        }

        if (strlen($password) < 8) {
            echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres']);
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        try {
            $result = $this->userModel->addUser($name, $email, $hashedPassword, $role, $position, $branch);
            echo json_encode(['success' => true, 'message' => 'Usuario agregado correctamente']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al agregar usuario: ' . $e->getMessage()]);
        }
    }

    private function editUser() 
    {
        $id_usuarios = $_POST['id_usuarios'] ?? '';
        $name = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? ''; 
        $role = $_POST['rol'] ?? null;
        $position = $_POST['puesto'] ?? 0;
        $branch = $_POST['sucursal'] ?? 0;
        $state = $_POST['estado'] ?? 'Activo';

        // Validaciones
        if (empty($id_usuarios) || empty($name) || empty($email) || !isset($role) || !isset($position) || !isset($branch) || empty($state)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido']);
            return;
        }

        $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : null;

        try {
            $result = $this->userModel->updateUser($id_usuarios, $name, $email, $role, $position, $branch, $state, $hashedPassword);
            echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar usuario: ' . $e->getMessage()]);
        }
    }

    private function deleteUser() 
    {
        $id_usuarios = $_GET['id_usuarios'] ?? ''; 

        // Validaciones
        if (empty($id_usuarios)) {
            echo json_encode(['success' => false, 'message' => 'ID de usuario no proporcionado']);
            return;
        }

        try {
            $result = $this->userModel->deleteUser($id_usuarios);
            echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar usuario: ' . $e->getMessage()]);
        }
    }

    private function getRoles() 
    {
        try {
            $roles = $this->userModel->getRoles();
            echo json_encode(['success' => true, 'roles' => $roles]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al obtener roles: ' . $e->getMessage()]);
        }
    }
}

// Instancia del controlador
try {
    $controller = new UserController();
    $controller->handleRequest();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Ocurrió un error inesperado: ' . $e->getMessage()]);
}
