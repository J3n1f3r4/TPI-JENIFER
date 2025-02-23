<?php
session_start();
include 'conexion.php'; // Asegúrate de incluir la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturar los valores desde el formulario
    $nombre = isset($_POST['userName']) ? $_POST['userName'] : null;
    $password = isset($_POST['userPassword']) ? $_POST['userPassword'] : null;

    // Verificar que los campos no estén vacíos
    if (empty($nombre) || empty($password)) {
        echo "Por favor, completa todos los campos.";
        exit();
    }

    // Usar consultas preparadas para evitar la inyección SQL
    $sql = "SELECT * FROM datos WHERE nombre = ? AND contraseña = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $nombre, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && mysqli_num_rows($result) > 0) {
        $usuario = $result->fetch_assoc();
        
        // Guardar datos relevantes en la sesión
        $_SESSION['usuario'] = $usuario['nombre'];  // Nombre del usuario
        $_SESSION['rol'] = $usuario['rol'];  // Rol del usuario
        $_SESSION['id'] = $usuario['id'];  // ID del usuario

        // Redirigir según el rol
        if ($usuario['rol'] == 'alumno') {
            header("Location: alumno.php");
            exit();
        } elseif ($usuario['rol'] == 'admin') {
            header("Location: usuarios.php");
            exit();
        } elseif ($usuario['rol'] == 'profesor') {
            header("Location: profesor.php");
            exit();
        }
    } else {
        echo "Credenciales incorrectas.";
    }
}
?>
