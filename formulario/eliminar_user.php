<?php
include("conexion.php");

if (isset($_POST['id'])) {
    $userId = $_POST['id'];
    $userId = mysqli_real_escape_string($conexion, $userId);

    // Realiza la consulta para eliminar el registro
    $consulta = "DELETE FROM alumno1 WHERE id = '$userId'";
    if (mysqli_query($conexion, $consulta)) {
        // Redirigir a la página principal o tabla
        header("Location: departamento.php");
        exit();
    } else {
        echo 'error';
    }
} else {
    // Redirigir si no hay datos
    echo "Acceso no autorizado.";
}
?>
