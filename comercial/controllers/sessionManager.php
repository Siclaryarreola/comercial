<?php
class SessionManager 
{
    const TIEMPO_MAXIMO_INACTIVIDAD = 1800; // 30 minutos

    public static function initSession() 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => $_SERVER['HTTP_HOST'],
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            session_start();
        }

        if (!isset($_SESSION['ultimo_tiempo_actividad'])) {
            $_SESSION['ultimo_tiempo_actividad'] = time();
        } else {
            self::checkSessionTimeout();
        }
    }

    public static function createSession($user) 
    {
        $_SESSION['user'] = $user;
        $_SESSION['ultimo_tiempo_actividad'] = time();
    }

    public static function destroySession() 
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                      $params["path"], $params["domain"],
                      $params["secure"], $params["httponly"]);
        }
        session_destroy();
        header('Location: ../index.php?controller=login&action=showLoginForm');
        exit;
    }
    
    


    private static function checkSessionTimeout() 
    {
        if (time() - $_SESSION['ultimo_tiempo_actividad'] > self::TIEMPO_MAXIMO_INACTIVIDAD) {
            self::destroySession();
        } else {
            $_SESSION['ultimo_tiempo_actividad'] = time();
        }
    }

    public static function authenticate() 
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ../index.php?controller=login&action=showLoginForm');
            exit();
        }
    }
}
?>
