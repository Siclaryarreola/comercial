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
    
        public function getUsersData() {
        $data = [];
        $data['usuarios'] = $this->userModel->getUsuarios();
        $data['puestos'] = $this->userModel->getPuestos();
        $data['sucursales'] = $this->userModel->getSucursales();
        $data['roles'] = $this->userModel->getRoles();
        return $data;
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
            break;
            default:
               
                break;
        }
    }

 private function addUser() {
    $name = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['rol'] ?? null;
    $position = $_POST['puesto'] ?? 0;
    $branch = $_POST['sucursal'] ?? 0;

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

 private function editUser() {
        $id_usuarios = trim($_POST['id_usuarios'] ?? '');

        if (empty($id_usuarios)) {
            echo json_encode(['success' => false, 'message' => 'El ID del usuario es obligatorio']);
            return;
        }

        $data = [];
        if (!empty($_POST['nombre'])) $data['nombre'] = trim($_POST['nombre']);
        if (!empty($_POST['correo'])) {
            $email = trim($_POST['correo']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido']);
                return;
            }
            $data['correo'] = $email;
        }
        if (!empty($_POST['puesto'])) $data['puesto'] = $_POST['puesto'];
        if (!empty($_POST['sucursal'])) $data['sucursal'] = $_POST['sucursal'];
        if (!empty($_POST['rol'])) $data['rol'] = $_POST['rol'];
        if (!empty($_POST['estado'])) $data['estado'] = $_POST['estado'];

        if (empty($data)) {
            echo json_encode(['success' => false, 'message' => 'No se enviaron campos para actualizar']);
            return;
        }

try {
    $result = $this->userModel->updateUser($id_usuarios, $data);
    error_log("Respuesta: " . json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente']));
    echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente']);
} catch (Exception $e) {
    error_log("Error al actualizar usuario: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al actualizar usuario: ' . $e->getMessage()]);
}

    }

    private function deleteUser() {
        $id_usuarios = $_POST['id_usuarios'] ?? '';
        if (empty($id_usuarios)) {
            echo json_encode(['success' => false, 'message' => 'ID de usuario no proporcionado']);
            return;
        }

        try {
            $result = $this->userModel->deleteUser($id_usuarios);
            echo json_encode(['success' => $result, 'message' => $result ? 'Usuario eliminado correctamente' : 'Error al eliminar usuario']);
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
