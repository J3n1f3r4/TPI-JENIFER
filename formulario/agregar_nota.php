<?php
session_start();

// Evitar que el navegador almacene en caché esta página
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: r.php"); // Redirigir al inicio de sesión si no está autenticado
    exit();
}

include("conexion.php");

// Verificar si el ID del alumno se pasa a través de la URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idAlumno = intval($_GET['id']);  // ID del alumno

    // Verificar si el formulario se ha enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener las notas del formulario
        $materia = mysqli_real_escape_string($conexion, $_POST['materia']);
        $nota01 = mysqli_real_escape_string($conexion, $_POST['nota01']);
        $nota02 = mysqli_real_escape_string($conexion, $_POST['nota02']);
        $notaC1 = mysqli_real_escape_string($conexion, $_POST['notaC1']);
        $nota1 = mysqli_real_escape_string($conexion, $_POST['nota1']);
        $nota2 = mysqli_real_escape_string($conexion, $_POST['nota2']);
        $notaC2 = mysqli_real_escape_string($conexion, $_POST['notaC2']);
        $notaF = mysqli_real_escape_string($conexion, $_POST['notaF']);

        // Crear el nombre dinámico de la tabla de notas
        $nombreTablaNotas = "notas_" . $idAlumno;

        // Insertar las notas en la tabla correspondiente
        $query = "INSERT INTO $nombreTablaNotas (materia, nota01, nota02, notaC1, nota1, nota2, notaC2, notaF) 
                  VALUES ('$materia', '$nota01', '$nota02', '$notaC1', '$nota1', '$nota2', '$notaC2', '$notaF')";

        if (mysqli_query($conexion, $query)) {
            // Redirigir a departamento.php después de agregar la nota
            header("Location: departamento.php?id=" . $idAlumno);
            exit(); // Asegurarse de que el script se detenga después de la redirección
        } else {
            echo "Error al agregar la nota: " . mysqli_error($conexion);
        }
    }
} else {
    echo "ID de alumno no especificado.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nota</title>
    <link rel="stylesheet" href="/formulario/css/agregar_nota.css">
</head>
<body>
    <main>
        <h1>Agregar Nota</h1>

        <form action="agregar_nota.php?id=<?php echo $idAlumno; ?>" method="POST">
            <label for="materia">Materia:</label>
            <input type="text" id="materia" name="materia" required>

            <label for="nota01">1º Informe:</label>
            <input type="number" id="nota01" name="nota01" step="1" required>

            <label for="nota02">2º Informe:</label>
            <input type="number" id="nota02" name="nota02" step="1" required>

            <label for="notaC1">1º Cuatrimestre:</label>
            <input type="number" id="notaC1" name="notaC1" step="1" required>

            <label for="nota1">1º Informe:</label>
            <input type="number" id="nota1" name="nota1" step="1" required>

            <label for="nota2">2º Informe:</label>
            <input type="number" id="nota2" name="nota2" step="1" required>

            <label for="notaC2">2º Cuatrimestre:</label>
            <input type="number" id="notaC2" name="notaC2" step="1" required>

            <label for="notaF">Nota Final:</label>
            <input type="number" id="notaF" name="notaF" step="1" required>

            <button type="submit">Guardar Nota</button>
        </form>
    </main>
</body>
</html>
