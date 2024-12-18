<?php
require_once('../config/database.php');

class UserModel 
{
    private $db;

    public function __construct() 
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getUsuarios() 
    {
        $query = "
            SELECT 
                u.id_usuarios, 
                u.nombre, 
                u.correo, 
                u.rol, 
                u.estado, 
                u.ultimo_acceso, 
                u.puesto, 
                u.sucursal 
            FROM usuarios u
            LEFT JOIN puestos p ON u.puesto = p.id_puestos
            LEFT JOIN sucursales s ON u.sucursal = s.id_sucursales
        ";
        $result = $this->db->query($query);
        if (!$result) {
            error_log("Error al obtener usuarios: " . $this->db->error);
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPuestos() 
    {
        $query = "SELECT * FROM puestos";
        $result = $this->db->query($query);
        if (!$result) {
            error_log("Error al obtener puestos: " . $this->db->error);
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getSucursales() 
    {
        $query = "SELECT * FROM sucursales";
        $result = $this->db->query($query);
        if (!$result) {
            error_log("Error al obtener sucursales: " . $this->db->error);
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getRoles() 
    {
        $query = "SELECT * FROM roles";
        $result = $this->db->query($query);
        if (!$result) {
            error_log("Error al obtener roles: " . $this->db->error);
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addUser($name, $email, $password, $role, $position, $branch) 
    {
        if (empty($name) || empty($email) || empty($password) || !isset($role) || !isset($position) || !isset($branch)) {
            throw new InvalidArgumentException("Todos los campos son obligatorios.");
        }

        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, correo, contraseÃ±a, rol, puesto, sucursal) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->db->error);
        }
        $stmt->bind_param("sssiii", $name, $email, $password, $role, $position, $branch);
        if (!$stmt->execute()) {
            throw new Exception("Error al agregar usuario: " . $stmt->error);
        }
        return true;
    }

public function updateUser($id_usuarios, $data) {
    if (empty($id_usuarios) || !is_numeric($id_usuarios)) {
        throw new InvalidArgumentException("El ID del usuario es obligatorio y debe ser num«±rico.");
    }

    // Comprobaci«Ñn de que los campos necesarios est«¡n presentes
    $fieldsToUpdate = ['nombre', 'correo', 'puesto', 'sucursal', 'rol', 'estado'];
    $setParts = [];
    $params = [];
    $types = '';

    // Generar las partes de la consulta SQL solo para los campos especificados que est«±n presentes
    foreach ($fieldsToUpdate as $field) {
        if (isset($data[$field])) {
            $setParts[] = "$field = ?";
            $params[] = $data[$field];
            $types .= $field === 'puesto' || $field === 'sucursal' || $field === 'rol' ? 'i' : 's';
        }
    }

    // Asegurarse de que hay al menos un campo para actualizar
    if (empty($setParts)) {
        throw new InvalidArgumentException("No se proporcionaron campos v«¡lidos para actualizar.");
    }

    // Agregar el ID al final de los par«¡metros
    $params[] = $id_usuarios;
    $types .= 'i';

    // Construir la consulta SQL final
    $query = "UPDATE usuarios SET " . implode(', ', $setParts) . " WHERE id_usuarios = ?";
    $stmt = $this->db->prepare($query);

    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $this->db->error);
    }

    // Vincular los par«¡metros y ejecutar la actualizaci«Ñn
    $stmt->bind_param($types, ...$params);
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }

    return true;
}


    public function deleteUser($id_usuarios) 
    {
        if (empty($id_usuarios)) {
            throw new InvalidArgumentException("ID de usuario no proporcionado.");
        }

        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id_usuarios = ?");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->db->error);
        }
        $stmt->bind_param("i", $id_usuarios);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar usuario: " . $stmt->error);
        }
        return true;
    }
}
