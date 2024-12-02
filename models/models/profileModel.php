<?php
require_once('../config/database.php');

class ProfileModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Obtener los datos del perfil por correo electrónico.
    public function getProfileByEmail($email)
    {
        $query = "
            SELECT 
                usuarios.id,
                usuarios.nombre,
                usuarios.correo,
                detalleusuarios.ultimo_acceso,
                detalleusuarios.fecha_creacion,
                IFNULL(detalleusuarios.foto_perfil, '../public/images/images_usuario.png') AS foto_perfil,
                detalleusuarios.foto_perfil, -- Incluye el nuevo campo de archivo cargado
                detalleusuarios.genero,
                roles.rol AS rol
            FROM 
                usuarios
            LEFT JOIN detalleusuarios ON usuarios.detalle_id = detalleusuarios.id
            LEFT JOIN roles ON usuarios.rol = roles.id
            WHERE 
                usuarios.id = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Actualizar la foto de perfil de un usuario.
    public function updateProfilePhoto($userId, $photoPath)
    {
        $query = "
            UPDATE detalleusuarios 
            SET foto_perfil = ? 
            WHERE id = (SELECT detalle_id FROM usuarios WHERE id = ?)
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $photoPath, $userId);

        return $stmt->execute();
    }

    // Actualizar información básica del perfil de un usuario.
    public function updateBasicProfileInfo($userId, $name, $gender)
    {
        $query = "
            UPDATE detalleusuarios 
            SET nombre = ?, genero = ? 
            WHERE id = (SELECT detalle_id FROM usuarios WHERE id = ?)
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssi", $name, $gender, $userId);

        return $stmt->execute();
    }

    // Actualizar la ruta del archivo cargado en la base de datos.
    public function updateUploadedFilePath($userId, $filePath)
    {
        $query = "
            UPDATE detalleusuarios 
            SET foto_perfil = ? 
            WHERE id = (SELECT detalle_id FROM usuarios WHERE id = ?)
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $filePath, $userId);

        return $stmt->execute();
    }
}
