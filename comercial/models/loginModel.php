<?php
require_once('config/database.php');

class LoginModel
{
    private $db;

    public function __construct() 
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getUserByEmailAndPassword($email, $password) 
    {
        // Comprobación y depuración del email recibido
       

        $sql = "
            SELECT id_usuarios, nombre, correo, contraseña, rol, puesto, sucursal, estado,
                   intentos_fallidos, ultimo_intento, ultimo_acceso, fecha_creacion, foto_perfil, genero
            FROM usuarios
            WHERE TRIM(LOWER(correo)) = ?
        ";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Error preparando la consulta SQL: " . $this->db->error);
            return false;
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Depurar si el usuario se encontró o no
        if ($user) 
        {
           

            // Comprobar si el usuario está bloqueado por intentos fallidos
            if ($user['intentos_fallidos'] >= 5)
            {
                if ($user['ultimo_intento'] !== NULL)
                {
                    $lastAttemptTime = new DateTime($user['ultimo_intento']);
                    $currentTime = new DateTime();
                    if (($currentTime->getTimestamp() - $lastAttemptTime->getTimestamp()) < 180) 
                    { 
                        return 'blocked';
                    }
                }
            }

            // Verificar la contraseña usando password_verify()
            if (password_verify($password, $user['contraseña'])) {
                // Resetear intentos fallidos e iniciar sesión correctamente
                $this->resetFailedAttempts($user['id_usuarios']);
                $this->updateLastAccess($user['id_usuarios']);
                return $user;
            } else {
                // Incrementar intentos fallidos
                $this->incrementFailedAttempts($user['id_usuarios']);
                error_log("Contraseña incorrecta para el usuario: {$email}");
                return 'incorrect_password';
            }
        } else {
            error_log("Usuario no encontrado para el email: {$email}");
        }
        
        return null;
    }

    private function incrementFailedAttempts($userId) 
    {
        $sql = "UPDATE usuarios SET intentos_fallidos = intentos_fallidos + 1, ultimo_intento = NOW() WHERE id_usuarios = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
        } else {
            error_log("Error preparando la consulta para incrementar intentos fallidos: " . $this->db->error);
        }
    }

    private function resetFailedAttempts($userId)
    {
        $sql = "UPDATE usuarios SET intentos_fallidos = 0 WHERE id_usuarios = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
        } else {
            error_log("Error preparando la consulta para resetear intentos fallidos: " . $this->db->error);
        }
    }

    private function updateLastAccess($userId)
    {
        $sql = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id_usuarios = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
        } else {
            error_log("Error preparando la consulta para actualizar último acceso: " . $this->db->error);
        }
    }

    // Método para registrar usuarios con contraseña hasheada
    public function addUser($name, $email, $password, $role, $position, $branch) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "
            INSERT INTO usuarios (nombre, correo, contraseña, rol, puesto, sucursal, fecha_creacion, intentos_fallidos, ultimo_intento, ultimo_acceso, foto_perfil, genero) 
            VALUES (?, ?, ?, ?, ?, ?, NOW(), 0, NULL, NULL, '', '')
        ";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssiss", $name, $email, $hashedPassword, $role, $position, $branch);
            if ($stmt->execute()) {
                error_log("Usuario insertado con éxito. ID: " . $this->db->insert_id);
                return true;
            } else {
                error_log("Error al insertar usuario: " . $stmt->error);
            }
        } else {
            error_log("Error preparando la consulta para agregar usuario: " . $this->db->error);
        }
        return false;
    }
}
?>
