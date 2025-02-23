<?php
session_start();
include("conexion.php");

// Verificar si se ha pasado el ID y la materia en la URL
if (isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['materia']) && !empty($_GET['materia'])) {
    $idAlumno = intval($_GET['id']);  // ID del alumno
    $materia = mysqli_real_escape_string($conexion, $_GET['materia']);  // Materia seleccionada

    $nombreTablaNotas = "notas_" . $idAlumno;  // Tabla de notas dinámica

    // Consultar las notas de la materia correspondiente a ese alumno
    $queryNotas = "SELECT * FROM $nombreTablaNotas WHERE materia = '$materia'";
    $resultadoNotas = mysqli_query($conexion, $queryNotas);

    // Verificar si la consulta devolvió resultados
    if ($resultadoNotas && mysqli_num_rows($resultadoNotas) > 0) {
        $nota = mysqli_fetch_assoc($resultadoNotas);  // Obtener las notas de la materia seleccionada

        // Consultar los datos del alumno (nombre, curso)
        $consultaAlumno = "SELECT nombre, curso FROM datos WHERE id = $idAlumno";
        $resultadoAlumno = mysqli_query($conexion, $consultaAlumno);

        if ($resultadoAlumno && mysqli_num_rows($resultadoAlumno) > 0) {
            $alumno = mysqli_fetch_assoc($resultadoAlumno);
            $nombreAlumno = htmlspecialchars($alumno['nombre']);
            $curso = htmlspecialchars($alumno['curso']);
        }
    } else {
        echo "No se encontraron notas para la materia seleccionada.";
        exit;
    }
} else {
    echo "ID de alumno o materia no especificados.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Notas</title>
    <link rel="stylesheet" href="/formulario/css/editarN.css"> <!-- Archivo CSS -->
    <script>
        // Validación en JavaScript para asegurar valores enteros entre 0 y 10
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('input[type="number"]').forEach(input => {
                input.addEventListener('input', () => {
                    if (input.value < 0) input.value = 0;
                    if (input.value > 10) input.value = 10;
                    input.value = Math.floor(input.value); // Convertir a entero
                });
            });
        });
    </script>
</head>
<body>
    <h1>Editar Notas del Alumno</h1>
    <h2>Alumno: <?php echo $nombreAlumno; ?> - Curso: <?php echo $curso; ?></h2>

    <form action="guardar_notas.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $idAlumno; ?>">
        <input type="hidden" name="materia" value="<?php echo $materia; ?>">

        <div class="form-group">
            <label for="nota01">1º Informe:</label>
            <input type="number" id="nota01" name="nota01" value="<?php echo $nota['nota01']; ?>" min="0" max="10" step="1" required>
        </div>
        <div class="form-group">
            <label for="nota02">2º Informe:</label>
            <input type="number" id="nota02" name="nota02" value="<?php echo $nota['nota02']; ?>" min="0" max="10" step="1" required>
        </div>
        <div class="form-group">
            <label for="notaC1">1º Cuatrimestre:</label>
            <input type="number" id="notaC1" name="notaC1" value="<?php echo $nota['notaC1']; ?>" min="0" max="10" step="1" required>
        </div>
        <div class="form-group">
            <label for="nota1">1º Informe:</label>
            <input type="number" id="nota1" name="nota1" value="<?php echo $nota['nota1']; ?>" min="0" max="10" step="1" required>
        </div>
        <div class="form-group">
            <label for="nota2">2º Informe:</label>
            <input type="number" id="nota2" name="nota2" value="<?php echo $nota['nota2']; ?>" min="0" max="10" step="1" required>
        </div>
        <div class="form-group">
            <label for="notaC2">2º Cuatrimestre:</label>
            <input type="number" id="notaC2" name="notaC2" value="<?php echo $nota['notaC2']; ?>" min="0" max="10" step="1" required>
        </div>
        <div class="form-group">
            <label for="notaF">Nota Final:</label>
            <input type="number" id="notaF" name="notaF" value="<?php echo $nota['notaF']; ?>" min="0" max="10" step="1" required>
        </div>

        <button type="submit" class="btn btn-success">Guardar Cambios</button>
    </form>
</body>
</html>
