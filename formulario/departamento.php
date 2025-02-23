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

// Verificar si se ha pasado el ID en la URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idAlumno = intval($_GET['id']);  // ID del alumno
    $nombreTablaNotas = "notas_" . $idAlumno;  // Tabla de notas dinámica

    // Verificar si existen notas para este alumno
    $queryNotas = "SHOW TABLES LIKE '$nombreTablaNotas'";
    $resultadoNotas = mysqli_query($conexion, $queryNotas);

    // Verificar si la tabla de notas existe
    if (mysqli_num_rows($resultadoNotas) > 0) {
        // Obtener las notas del alumno
        $queryNotas = "SELECT * FROM $nombreTablaNotas";
        $resultadoNotas = mysqli_query($conexion, $queryNotas);

        // Obtener nombre y curso del alumno
        $consulta = "SELECT nombre, curso FROM datos WHERE id = $idAlumno";
        $resultado = mysqli_query($conexion, $consulta);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $alumno = mysqli_fetch_assoc($resultado);
            $nombreAlumno = htmlspecialchars($alumno['nombre']);
            $curso = htmlspecialchars($alumno['curso']);
        }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas del Alumno</title>
    <link rel="stylesheet" href="/formulario/css/departamento.css">
</head>
<body>
    <!-- Menú de navegación -->
    <nav>
        <div class="container__logo">
            <img src="LogoS.png" alt="Logo Escuela">
            <span class="school-name">Colegios Provincial Dr. Ernesto Guevara</span>
        </div>
        <div class="dropdown">
            <button class="btn-sesion">Menú</button>
            <div class="dropdown-content">
                <li>
                    <button class="btn-menu" onclick="imprimirTabla()">Imprimir Notas</button>
                </li>
                <a class="btn-menu" href="cerrarSesion.php">
                    <button>Cerrar Sesión</button>
                </a>
                <a class="btn-menu" href="usuarios.php">
                    <button>Usuarios</button>
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="contenido">
        <h1>Notas del Alumno</h1>
        <div class="datos-alumno">
            <h2>Nombre: <?php echo $nombreAlumno; ?></h2>
            <h2>Curso: <?php echo $curso; ?></h2>
        </div>

        <!-- Botón para agregar nota -->
<div class="btn-agregar-nota-container">
    <a href="agregar_nota.php?id=<?php echo $idAlumno; ?>" 
       class="btn-agregar-nota">
       Agregar Nota
    </a>
</div>


        <?php if (mysqli_num_rows($resultadoNotas) > 0) { ?>
            <table id="tableNotas">
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>1º Informe </th>
                        <th>2º Informe </th>
                        <th>1º Cuatrimestre</th>
                        <th>1º Informe </th>
                        <th>2º Informe </th>
                        <th>2º Cuatrimestre</th>
                        <th>Nota Final</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($nota = mysqli_fetch_assoc($resultadoNotas)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($nota['materia']); ?></td>
                        <td><?php echo htmlspecialchars($nota['nota01']); ?></td>
                        <td><?php echo htmlspecialchars($nota['nota02']); ?></td>
                        <td><?php echo htmlspecialchars($nota['notaC1']); ?></td>
                        <td><?php echo htmlspecialchars($nota['nota1']); ?></td>
                        <td><?php echo htmlspecialchars($nota['nota2']); ?></td>
                        <td><?php echo htmlspecialchars($nota['notaC2']); ?></td>
                        <td><?php echo htmlspecialchars($nota['notaF']); ?></td>
                        <td>
                            <div class="btn-container">
                                <a href="editar_nota.php?id=<?php echo $idAlumno; ?>&materia=<?php echo urlencode($nota['materia']); ?>" class="btn-modificar">Modificar Nota</a>
                                <a href="eliminar_nota.php?id=<?php echo $idAlumno; ?>&materia=<?php echo urlencode($nota['materia']); ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro de eliminar esta nota?')">Eliminar</a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No se han registrado notas para este alumno.</p>
        <?php } ?>
    </main>
    <script>
function imprimirTabla() {
    const nombreColegio = "Colegio Provincial Dr. Ernesto Guevara";
    const logoURL = "LogoS.png";
    const nombreUsuario = "<?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Desconocido'; ?>";
    const nombreAlumno = "<?php echo isset($nombreAlumno) ? $nombreAlumno : 'Alumno Desconocido'; ?>";
    const curso = "<?php echo isset($curso) ? $curso : 'Curso Desconocido'; ?>";
    
    const tablaOriginal = document.getElementById('tableNotas');
    if (!tablaOriginal) {
        alert("Error: No se encontró la tabla de notas.");
        return;
    }

    // Clonar la tabla para modificarla sin afectar la original
    const tablaClonada = tablaOriginal.cloneNode(true);

    // Eliminar la última columna de cada fila (Acciones)
    const filas = tablaClonada.querySelectorAll("tr");
    filas.forEach((fila) => {
        if (fila.cells.length > 0) {
            fila.deleteCell(-1); // Elimina la última celda de cada fila (Acciones)
        }
    });

    const contenido = `
        <div style="display: flex; align-items: center; justify-content: flex-start; margin-bottom: 20px;">
            <img src="${logoURL}" alt="Logo" style="width: 100px; margin-right: 20px;">
            <h1 style="font-size: 20px; margin: 0;">${nombreColegio}</h1>
        </div>
        <h2 style="text-align: center; font-size: 20px; margin: 10px 0;">Notas del Alumno</h2>
        <p><strong>Nombre del Alumno:</strong> ${nombreAlumno}</p>
        <p><strong>Curso:</strong> ${curso}</p>
        <p><strong>Nombre del Usuario:</strong> ${nombreUsuario}</p>
        <br>
        ${tablaClonada.outerHTML}
    `;

    const ventanaImpresion = window.open("", "_blank");
    if (!ventanaImpresion) {
        alert("Error: El bloqueador de ventanas emergentes puede estar activado.");
        return;
    }

    ventanaImpresion.document.write(`
        <html>
            <head>
                <title>Registro de Notas</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
                    th { background-color: #f9f9f9; }
                </style>
            </head>
            <body>
                ${contenido}
            </body>
        </html>
    `);

    ventanaImpresion.document.close();
    ventanaImpresion.print();
}

</script>



    <footer style="background-color: #333; color: white; width: 100vw; margin: 0; padding: 10px 0; box-sizing: border-box;">
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; max-width: 1200px; margin: 0 auto; align-items: center; padding: 0 10px;">
            <!-- Información del colegio -->
            <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px;">
                <h3 style="margin: 5px 0; font-size: 16px;">Colegio Provincial Dr. Ernesto Guevara</h3>
                <li><p style="margin: 5px 0; font-size: 14px;">Teléfono: (555) 123-4567</p></li>
                <li><p style="margin: 5px 0; font-size: 14px;">Email: contacto@colegio.com</p></li>
                <li><p style="margin: 5px 0; font-size: 14px;">Ubicación: Calle Principal #123, Ciudad</p></li>
            </ul>

            <!-- Menú -->
            <div style="flex: 1 1 150px; margin: 5px;">
                <ul style="list-style: none; padding: 0px; margin: 0px; font-size: 14px;">
                    <h3 style="margin: 5px 0; font-size: 16px;">Menú</h3>
                    <li style="margin: 3px 0;"><a href="#inicio" style="color: white; text-decoration: none;">Inicio</a></li>
                    <li style="margin: 3px 0;"><a href="#nosotros" style="color: white; text-decoration: none;">Nosotros</a></li>
                    <li style="margin: 3px 0;"><a href="#productos" style="color: white; text-decoration: none;">Productos</a></li>
                    <li style="margin: 3px 0;"><a href="#contacto" style="color: white; text-decoration: none;">Contacto</a></li>
                </ul>
            </div>

            <!-- Redes Sociales -->
            <div style="flex: 1 1 150px; margin: 5px;">
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px;">
                    <h3 style="margin: 5px 0; font-size: 16px;">Síguenos</h3>
                    <li><a href="https://facebook.com" style="color: white; text-decoration: none;" target="_blank" rel="noopener noreferrer">Facebook</a></li>
                    <li><a href="https://twitter.com" style="color: white; text-decoration: none;" target="_blank" rel="noopener noreferrer">Twitter</a></li>
                    <li><a href="https://instagram.com" style="color: white; text-decoration: none;" target="_blank" rel="noopener noreferrer">Instagram</a></li>
                </ul>
            </div>

            <!-- Suscripción -->
            <div style="flex: 1 1 200px; margin: 5px;">
                <h3 style="margin: 5px 0; font-size: 16px;">Suscríbete</h3>
                <form style="display: flex; gap: 5px; align-items: center;">
                    <input type="email" placeholder="Correo electrónico" style="flex: 1; padding: 5px; font-size: 14px;">
                    <button type="submit" style="background-color: #007bff; color: white; border: none; padding: 5px 10px; font-size: 14px; cursor: pointer;">Enviar</button>
                </form>
            </div>
        </div>
        <div style="text-align: center; margin-top: 10px; font-size: 14px;">
            <p style="margin: 0;">&copy; 2025 Tecno-Lab S.A. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>
<?php
    } else {
        echo "ID de alumno no especificado.";
    }
}
?>
