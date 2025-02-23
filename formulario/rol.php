<?php
include "conexion.php";
if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

$hashedPassword = password_hash($contraseña, PASSWORD_DEFAULT);
if ($result && mysqli_num_rows($result) > 0) {
    $usuario = mysqli_fetch_assoc($result);
    if (password_verify($contraseña, $usuario['contraseña'])) {
        $_SESSION["user"] = $usuario;
        echo "Inicio de sesión exitoso"; // Para depurar
        // Redirección aquí
    } else {
        echo "Contraseña incorrecta"; // Para depurar
    }
} else {
    echo "Usuario no encontrado"; // Para depurar
}

?>