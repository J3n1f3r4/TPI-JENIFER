<!-- Cumple la funcion de guardar las notas del alumno -->
<?php
session_start();
include("conexion.php");

// Verificar si se han recibido los datos necesarios desde el formulario
if (isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['materia']) && !empty($_POST['materia']) &&
    isset($_POST['nota01']) && isset($_POST['nota02']) && isset($_POST['notaC1']) &&
    isset($_POST['nota1']) && isset($_POST['nota2']) && isset($_POST['notaC2']) && isset($_POST['notaF'])) {

    // Obtener los datos recibidos
    $idAlumno = intval($_POST['id']);  // ID del alumno
    $materia = mysqli_real_escape_string($conexion, $_POST['materia']);  // Materia
    $nota01 = floatval($_POST['nota01']);  // Nota 1º Informe
    $nota02 = floatval($_POST['nota02']);  // Nota 2º Informe
    $notaC1 = floatval($_POST['notaC1']);  // Nota 1º Cuatrimestre
    $nota1 = floatval($_POST['nota1']);    // Nota 1º Informe
    $nota2 = floatval($_POST['nota2']);    // Nota 2º Informe
    $notaC2 = floatval($_POST['notaC2']);  // Nota 2º Cuatrimestre
    $notaF = floatval($_POST['notaF']);    // Nota Final

    // Crear el nombre de la tabla de notas del alumno
    $nombreTablaNotas = "notas_" . $idAlumno;

    // Actualizar las notas en la tabla de notas correspondiente
    $query = "UPDATE $nombreTablaNotas SET 
                nota01 = '$nota01', 
                nota02 = '$nota02', 
                notaC1 = '$notaC1', 
                nota1 = '$nota1', 
                nota2 = '$nota2', 
                notaC2 = '$notaC2', 
                notaF = '$notaF' 
              WHERE materia = '$materia'";

    // Ejecutar la consulta
    if (mysqli_query($conexion, $query)) {
        // Redirigir a una página de éxito o mostrar mensaje de éxito
        header("Location: departamento.php?id=$idAlumno");  // Redirige a la página de notas del alumno
        exit;
    } else {
        echo "Error al actualizar las notas: " . mysqli_error($conexion);
    }

} else {
    echo "Faltan datos para actualizar las notas.";
}
?>
