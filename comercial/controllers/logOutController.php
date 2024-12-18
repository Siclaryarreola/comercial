<?php
class Logout 
{
    public function __construct() 
    {
        //accede a la funcion
        $this->executeLogout();
    }

    private function executeLogout() 
    {
        session_start(); // Asegurar que la sesión está iniciada
        session_unset(); // Limpiar todas las variables de sesión
        session_destroy(); // Destruir la sesión

        header('Location: ../index.php');  // Regresa al login si hay error
        exit;
    }
}
// Crear una instancia de Logout al cargar este archivo
new Logout();