<!-- Elimina las notas del alumno -->
<?php
session_start();
include("conexion.php");

// Verificar si se ha pasado el ID del alumno y la materia
if (isset($_GET['id']) && isset($_GET['materia']) && !empty($_GET['id']) && !empty($_GET['materia'])) {
    $idAlumno = intval($_GET['id']);  // ID del alumno
    $materia = mysqli_real_escape_string($conexion, $_GET['materia']);  // Materia a eliminar
    $nombreTablaNotas = "notas_" . $idAlumno;  // Tabla de notas dinámica

    // Eliminar la nota correspondiente a la materia
    $queryEliminar = "DELETE FROM $nombreTablaNotas WHERE materia = '$materia'";

    if (mysqli_query($conexion, $queryEliminar)) {
        // Si la eliminación fue exitosa, redirigir al usuario a la página de notas
        header("Location: departamento.php?id=$idAlumno");  // Redirige a la página de notas del alumno
        exit();
    } else {
        // En caso de error
        echo "Error al eliminar la nota: " . mysqli_error($conexion);
    }
} else {
    echo "Faltan parámetros para eliminar la nota.";
}
?>
