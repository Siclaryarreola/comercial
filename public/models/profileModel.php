<?php
require_once('../config/database.php');

class ProfileModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Obtener los datos del perfil por ID de usuario.
    public function getProfileById($userId)
    {
        $query = "
            SELECT 
                id_usuarios,
                nombre,
                correo,
                ultimo_acceso,
                fecha_creacion,
                IFNULL(foto_perfil, '../public/images/images_usuario.png') AS foto_perfil,
                genero,
                estado
            FROM 
                usuarios
            WHERE 
                id_usuarios = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Actualizar la foto de perfil de un usuario.
    public function updateProfilePhoto($userId, $photoPath)
    {
        $query = "UPDATE usuarios SET foto_perfil = ? WHERE id_usuarios = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $photoPath, $userId);

        return $stmt->execute();
    }
}
