<?php
require_once('../models/profileModel.php');

class ProfileController
{
    private $profileModel;

    public function __construct()
    {
        $this->profileModel = new ProfileModel();
    }

    // Obtener los datos del perfil utilizando el ID de usuario almacenado en sesión.
    public function getProfileData()
    {
        $userId = $_SESSION['user']['id_usuarios'] ?? null;
        if (!$userId) {
            throw new Exception("Usuario no autenticado.");
        }

        $profileData = $this->profileModel->getProfileById($userId);
        if (!$profileData) {
            throw new Exception("No se encontraron datos del perfil del usuario.");
        }

        return $profileData;
    }

    // Actualizar la foto de perfil del usuario.
    public function updateProfilePhoto()
    {
        $userId = $_SESSION['user']['id_usuarios'] ?? null;

        if (!$userId || $_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['foto_perfil'])) {
            $_SESSION['error'] = "Solicitud no válida.";
            header("Location: ../views/profile.php");
            exit;
        }

        $file = $_FILES['foto_perfil'];

        // Validar el archivo subido
        if (!$this->validateUpload($file)) {
            $_SESSION['error'] = "El archivo no cumple con los requisitos.";
            header("Location: ../views/profile.php");
            exit;
        }

        // Generar una ruta única para el archivo
        $destinationPath = "../photos/" . uniqid() . "_" . basename($file['name']);

        // Crear el directorio si no existe
        if (!is_dir('../photos/')) {
            mkdir('../photos/', 0777, true);
        }

        // Mover el archivo al directorio deseado
        if (move_uploaded_file($file['tmp_name'], $destinationPath)) {
            // Guardar la ruta del archivo en la base de datos
            if (!$this->profileModel->updateProfilePhoto($userId, $destinationPath)) {
                $_SESSION['error'] = "Error al guardar la foto en la base de datos.";
                header("Location: ../views/profile.php");
                exit;
            }
            $_SESSION['success'] = "Foto de perfil actualizada correctamente.";
        } else {
            $_SESSION['error'] = "Error al subir el archivo.";
            header("Location: ../views/profile.php");
            exit;
        }

        header("Location: profile.php");
        exit;
    }

    // Validar el archivo subido.
    private function validateUpload($file)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false; // Error en la carga del archivo.
        }

        // Tipos de archivo permitidos
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            return false; // Tipo de archivo no permitido.
        }

        // Validar el tamaño del archivo (2 MB máximo)
        $maxSize = 2 * 1024 * 1024; // 2 MB en bytes
        if ($file['size'] > $maxSize) {
            return false; // Tamaño no permitido.
        }

        return true; // El archivo es válido.
    }
}
