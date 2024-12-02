<?php
class authController 
{

    // Enviar el correo electrónico con el enlace de recuperación de contraseña
    public function sendPasswordReset() 
    {
        $email = trim($_POST['email']);
        $userModel = new UserModel();
        
        // Verificar si el correo está registrado
        $user = $userModel->getUserByEmail($email);
        if (!$user) 
        {
            $_SESSION['error'] = "No se encontró una cuenta con ese correo electrónico.";
            header('Location: index.php?controller=auth&action=forgotPassword');
            exit();
        }

        // Generar un token único de recuperación
        $token = bin2hex(random_bytes(16));
        
        // Guardar el token en la base de datos
        $userModel->savePasswordResetToken($email, $token);

        // Enlace de recuperación que se enviará por correo
       // $resetLink = "http://localhost:8081/comercial/index.php?controller=auth&action=resetPassword&token=$token";
        mail($email, "Recuperación de contraseña", "Haga clic en este enlace para recuperar su contraseña: $resetLink");

        // Redirigir con mensaje de éxito
        $_SESSION['success'] = "Se ha enviado un correo con las instrucciones de recuperación.";
        header('Location: index.php?controller=auth&action=forgotPassword');
    }

    // Verificar el token de recuperación y mostrar la página de restablecimiento de contraseña
    public function resetPassword() 
    {
        $token = $_GET['token'] ?? '';
        $userModel = new UserModel();
        
        // Verificar si el token es válido y no ha expirado
        $user = $userModel->verifyPasswordResetToken($token);
        if (!$user) 
        {
            echo "<p class='text-center text-danger'>Error: Token no válido o ha expirado.</p>";
            exit();
        }

        // Cargar la vista de restablecimiento de contraseña
        include('views/resetPass.php');
    }

    // Actualizar la contraseña después de verificar el token
    public function updatePassword() 
    {
        $token = $_POST['token'] ?? '';
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        // Verificar que ambas contraseñas coincidan
        if ($newPassword !== $confirmPassword) 
        {
            echo "<p class='text-center text-danger'>Error: Las contraseñas no coinciden.</p>";
            exit();
        }

        // Hashear la nueva contraseña y actualizarla en la base de datos
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $userModel = new UserModel();

        if ($userModel->updatePassword($token, $hashedPassword)) 
        {
            echo "<p class='text-center text-success'>Contraseña actualizada correctamente.</p>";
        } else {
            echo "<p class='text-center text-danger'>Error al actualizar la contraseña.</p>";
        }
    }
}
?>
