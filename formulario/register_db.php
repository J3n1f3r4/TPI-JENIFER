<?php
include "conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["userName"] ?? '');
    $userEmail = trim($_POST["userEmail"] ?? '');
    $userPassword = trim($_POST["userPassword"] ?? '');

    // Validar campos obligatorios
    if (empty($nombre) || empty($userEmail) || empty($userPassword)) {
        echo "
        <script> 
            alert('Todos los campos son obligatorios.');
            window.location= 'r.php';
        </script>";
        exit();
    }

    // Verificar si el correo ya existe
    $checkEmail = mysqli_query($conexion, "SELECT * FROM datos WHERE email='$userEmail'");
    if ($checkEmail && mysqli_num_rows($checkEmail)) {
        echo "
        <script> 
            alert('Este correo ya existe en la Base de Datos');
            window.location= 'r.php';
        </script>";
        exit();
    }

    // Verificar si el nombre ya existe
    $checkUserName = mysqli_query($conexion, "SELECT * FROM datos WHERE nombre='$nombre'");
    if ($checkUserName && mysqli_num_rows($checkUserName)) {
        echo "
        <script> 
            alert('Este usuario ya existe en la Base de Datos');
            window.location= 'r.php';
        </script>";
        exit();
    }

    // Insertar datos
    $query = "INSERT INTO datos(nombre, email, contraseña) VALUES ('$nombre', '$userEmail', '$userPassword')";
    if (mysqli_query($conexion, $query)) {
        echo "
        <script> 
            alert('Te registraste correctamente');
            window.location= 'r.php';
        </script>";
    } else {
        echo "
        <script> 
            alert('Error al registrarse. Intenta de nuevo.');
            window.location= 'r.php';
        </script>";
    }

    mysqli_close($conexion);
}
?>
