<?php
require_once ('../models/userModel.php'); // Ruta absoluta

class UserController 
{
    private $userModel;

    public function __construct() 
    {
        $this->userModel = new userModel();
        // Validar la existencia de la clase
        if (!class_exists('userModel')) {
            die('Error: Clase userModel no encontrada.');
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
                echo json_encode(['success' => false, 'message' => 'Acci¨®n no v¨¢lida']);
                break;
        }
    }

    private function addUser() 
    {
        $name = $_POST['nombre'] ?? '';
        $email = $_POST['correo'] ?? '';
        $password = $_POST['password'] ?? ''; 
        $role = $_POST['rol'] ?? null;
        $position = $_POST['puesto'] ?? 0;
        $branch = $_POST['sucursal'] ?? 0;

        if (empty($name) || empty($email) || empty($password) || !isset($role) || !isset($position) || !isset($branch)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
            return;
        }

        $result = $this->userModel->addUser($name, $email, $password, $role, $position, $branch);

        echo json_encode($result 
            ? ['success' => true, 'message' => 'Usuario agregado correctamente']
            : ['success' => false, 'message' => 'Error al agregar usuario']
        );
    }

    private function editUser() 
    {
        $id = $_POST['id'] ?? '';
        $name = $_POST['nombre'] ?? '';
        $email = $_POST['correo'] ?? '';
        $password = $_POST['password'] ?? ''; 
        $role = $_POST['rol'] ?? null;
        $position = $_POST['puesto'] ?? 0;
        $branch = $_POST['sucursal'] ?? 0;
        $state = $_POST['estado'] ?? 'Activo';

        if (empty($id) || empty($name) || empty($email) || !isset($role) || !isset($position) || !isset($branch) || empty($state)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
            return;
        }

        $result = $this->userModel->updateUser($id, $name, $email, $role, $position, $branch, $state);

        echo json_encode($result 
            ? ['success' => true, 'message' => 'Usuario actualizado correctamente']
            : ['success' => false, 'message' => 'Error al actualizar usuario']
        );
    }

    private function deleteUser() 
    {
        $id = $_GET['id'] ?? ''; 

        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID de usuario no proporcionado']);
            return;
        }

        $result = $this->userModel->deleteUser($id);

        echo json_encode($result 
            ? ['success' => true, 'message' => 'Usuario eliminado correctamente']
            : ['success' => false, 'message' => 'Error al eliminar usuario']
        );
    }

    private function getRoles() 
    {
        $roles = $this->userModel->getRoles();
        echo json_encode(['success' => true, 'roles' => $roles]);
    }
}

// Instancia del controlador
try {
    $controller = new UserController();
    $controller->handleRequest();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Ocurri¨® un error inesperado: ' . $e->getMessage()]);
}
