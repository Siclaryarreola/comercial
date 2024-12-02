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
        // Modificamos la consulta para incluir los JOIN con las tablas de puestos y sucursales
        $query = "
            SELECT 
                u.id_usuarios, 
                u.nombre, 
                u.correo, 
                u.rol, 
                u.estado, 
                u.ultimo_acceso, 
                p.puesto , 
                s.sucursal 
            FROM usuarios u
            LEFT JOIN puestos p ON u.puesto = p.id_puesto
            LEFT JOIN sucursales s ON u.sucursal = s.id
        ";
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getPuestos() 
    {
        $query = "SELECT * FROM puestos";
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getSucursales() 
    {
        $query = "SELECT * FROM sucursales";
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getRoles() 
    {
        $query = "SELECT * FROM roles";
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function addUser($name, $email, $password, $role, $position, $branch) 
    {
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, correo, password, rol, puesto, sucursal) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiii", $name, $email, $password, $role, $position, $branch);
        return $stmt->execute();
    }

    public function updateUser($id, $name, $email, $role, $position, $branch, $state) 
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET nombre = ?, correo = ?, rol = ?, puesto = ?, sucursal = ?, estado = ? WHERE id = ?");
        $stmt->bind_param("sssiiii", $name, $email, $role, $position, $branch, $state, $id);
        return $stmt->execute();
    }

    public function deleteUser($id) 
    {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
