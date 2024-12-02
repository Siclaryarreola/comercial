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
                p.puesto AS puesto, 
                s.sucursal AS sucursal
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

        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, correo, password, rol, puesto, sucursal) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->db->error);
        }
        $stmt->bind_param("sssiii", $name, $email, $password, $role, $position, $branch);
        if (!$stmt->execute()) {
            throw new Exception("Error al agregar usuario: " . $stmt->error);
        }
        return true;
    }

    public function updateUser($id_usuarios, $name, $email, $role, $position, $branch, $state) 
    {
        if (empty($id_usuarios) || empty($name) || empty($email) || !isset($role) || !isset($position) || !isset($branch) || empty($state)) {
            throw new InvalidArgumentException("Todos los campos son obligatorios.");
        }

        $stmt = $this->db->prepare("UPDATE usuarios SET nombre = ?, correo = ?, rol = ?, puesto = ?, sucursal = ?, estado = ? WHERE id_usuarios = ?");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->db->error);
        }
        $stmt->bind_param("sssiiii", $name, $email, $role, $position, $branch, $state, $id_usuarios);
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar usuario: " . $stmt->error);
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
