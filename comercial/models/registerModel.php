<?php
require_once('config/database.php'); // Ruta a la configuración de la base de datos

class RegisterModel 
{
    private $db;

    public function __construct() 
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Función para crear un usuario con los datos de nombre, email, contraseña, puesto y sucursal
  public function createUser($name, $email, $password, $puesto, $sucursal) 
{
    // Hashea la contraseña para almacenarla de manera segura
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Inserta el usuario en la tabla `usuarios`
    $sqlUsuario = "INSERT INTO usuarios (nombre, correo, contraseña, puesto, sucursal, estado, intentos_fallidos, fecha_creacion) 
                   VALUES (?, ?, ?, ?, ?, 'Activo', 0, NOW())";
    $stmtUsuario = $this->db->prepare($sqlUsuario);

    if (!$stmtUsuario) {
        error_log("Error preparando la consulta para usuarios: " . $this->db->error);
        return false;
    }

    // Vincula los parámetros a la consulta SQL de `usuarios`
    $stmtUsuario->bind_param("sssii", $name, $email, $hashedPassword, $puesto, $sucursal);
    
    if (!$stmtUsuario->execute()) {
        error_log("Error ejecutando la inserción en usuarios: " . $stmtUsuario->error);
        return false;
    }

    // Verifica si se insertó correctamente en `usuarios`
    if ($stmtUsuario->affected_rows === 1) {
        return $this->db->insert_id; // Devuelve el ID del nuevo usuario
    } else {
        error_log("No se pudo insertar el usuario en la tabla usuarios.");
        return false;
    }
}
/* ------------------------------------------------------------------------------------------------------------*/

    // Función para obtener todas las sucursales desde la tabla `sucursales`
    public function getSucursales() 
    {
        $sucursales = [];
        $sql = "SELECT id_sucursales, sucursal FROM sucursales ORDER BY sucursal ASC";
        $result = $this->db->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sucursales[] = $row;
            }
        }
        
        return $sucursales;
    }

    //Función para obtener todos los puestos desde la tabla `puestos`
    public function getPuestos() 
    {
        $puestos = [];
        $sql = "SELECT id_puestos, puesto FROM puestos ORDER BY puesto ASC";
        $result = $this->db->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $puestos[] = $row;
            }
        }

        return $puestos;
    }
}
?>