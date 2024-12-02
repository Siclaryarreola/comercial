<?php
// Suponiendo que esta es la contraseña que el usuario ingresó
$passwordIngresada = "12345678";

// Suponiendo que este es el hash que se obtuvo de la base de datos para este usuario
$hashedPassword = '$2y$10$oiBY0I/PoxLF46KTxcr1lecS69VCQPAthOghgGdPiNTmvp/T9zqFm'; // Reemplaza con el hash real

// Verifica si la contraseña ingresada coincide con el hash
if (password_verify($passwordIngresada, $hashedPassword)) {
    echo "La contraseña es correcta";
} else {
    echo "La contraseña es incorrecta";
}
?>
