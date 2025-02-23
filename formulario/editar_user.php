<!-- este tiene la funcion de crear una tabla para cada alumno con su id -->
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

// Verificar si se ha proporcionado el ID en la URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    die("ID de usuario no especificado.");
}

// Consultar los datos del usuario
$query = "SELECT * FROM datos WHERE id = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

// Verificar si el usuario existe
if ($resultado->num_rows === 0) {
    die("Usuario no encontrado.");
}

$usuario = $resultado->fetch_assoc();

// Si el formulario se envía, actualizar el usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $DNI = $_POST['DNI'];
    $curso = $_POST['curso'];
    $rol = $_POST['rol'];

    // Actualizar los datos del usuario en la base de datos
    $queryUpdate = "UPDATE datos SET nombre = ?, email = ?, DNI = ?, curso = ?, rol = ? WHERE id = ?";
    $stmtUpdate = $conexion->prepare($queryUpdate);
    $stmtUpdate->bind_param("ssissi", $nombre, $email, $DNI, $curso, $rol, $id);

    if ($stmtUpdate->execute()) {
        // Si el rol es 'alumno', crear la tabla de notas
        if ($rol == 'alumno') {
            // Crear una tabla para este alumno en particular
            $nombreTablaNotas = "notas_" . $id; // Crear un nombre único para la tabla basado en el ID del usuario
            $createTableQuery = "
                CREATE TABLE IF NOT EXISTS $nombreTablaNotas (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    materia VARCHAR(100),
                    nota01 INT,     -- Cambiado de DECIMAL a INT
                    nota02 INT,     -- Cambiado de DECIMAL a INT
                    notaC1 INT,     -- Cambiado de DECIMAL a INT
                    nota1 INT,      -- Cambiado de DECIMAL a INT
                    nota2 INT,      -- Cambiado de DECIMAL a INT
                    notaC2 INT,     -- Cambiado de DECIMAL a INT
                    notaF INT       -- Cambiado de DECIMAL a INT
                )
            ";

            // Ejecutar la creación de la tabla
            if ($conexion->query($createTableQuery) === TRUE) {
                echo "La tabla de notas para el alumno se creó correctamente.";
            } else {
                echo "Error al crear la tabla de notas: " . $conexion->error;
            }
        }

        // Redirigir a usuarios.php después de actualizar
        header("Location: usuarios.php");
        exit();  // Asegúrate de llamar a exit después de header()
    } else {
        echo "Error al actualizar el usuario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" type="text/css" href="/formulario/css/editarUser.css">
</head>
<body>
    <div class="form-container">
        <h1>Editar Usuario</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Correo:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="DNI">DNI:</label>
                <input type="number" id="DNI" name="DNI" value="<?php echo htmlspecialchars($usuario['DNI']); ?>" required>
            </div>

            <div class="form-group">
                <label for="curso">Curso:</label>
                <input type="text" id="curso" name="curso" value="<?php echo htmlspecialchars($usuario['curso']); ?>" required>
            </div>

            <div class="form-group">
                <label for="rol">Rol:</label>
                <select name="rol" id="rol" required>
                    <option value="admin" <?php echo $usuario['rol'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="alumno" <?php echo $usuario['rol'] == 'alumno' ? 'selected' : ''; ?>>Alumno</option>
                    <option value="profesor" <?php echo $usuario['rol'] == 'profesor' ? 'selected' : ''; ?>>Profesor</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit">Guardar Cambios</button>
                <a href="usuarios.php" class="cancel-btn">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
