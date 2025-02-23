<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: r.php");
    exit();
}

$id = $_SESSION['id']; // Asegúrate de que este valor exista y sea válido

include("conexion.php");
$curso = 'No especificado'; // Valor predeterminado para el curso
$resultadoNotas = null; // Por defecto, no hay notas

// Verificar la conexión
if (!$conexion) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener el curso del alumno
$query = "SELECT * FROM datos WHERE id = ?";
$stmt = $conexion->prepare($query);

if (!$stmt) {
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();
    $curso = $usuario['curso'] ?? 'No especificado'; // Asignar el curso si está disponible
} else {
    die("Error: Usuario no encontrado.");
}

// Construir el nombre de la tabla de notas
$tablaNotas = "notas_" . $id; 

// Verificar si la tabla existe
$checkTableQuery = "SHOW TABLES LIKE '$tablaNotas'";
$tableExists = $conexion->query($checkTableQuery);

if ($tableExists && $tableExists->num_rows > 0) {
    // Consultar las notas
    $queryNotas = "SELECT * FROM $tablaNotas";
    $resultadoNotas = $conexion->query($queryNotas);

    if (!$resultadoNotas) {
        $mensajeError = "Error al obtener las notas: " . $conexion->error;
    }
} else {
    $mensajeError = "No se encontraron registros de notas para este alumno.";
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas del Alumno</title>
    <link rel="stylesheet" href="/formulario/css/alumno.css">
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
        </div>
    </div>
</nav>

<h1 style="color: black; text-align: center; font-size: 50px; font-weight: bold;">Bienvenido Alumno: <?php echo $_SESSION['usuario']; ?></h1>

<div id="tablaNotas">
    <h2>Notas de: <?php echo $_SESSION['usuario']; ?><br> Curso: <?php echo $curso; ?></h2>

    <?php if (isset($mensajeError)) { ?>
        <p style="color: red; text-align: center;"><?php echo $mensajeError; ?></p>
    <?php } else { ?>
        <table>
            <thead>
                <tr>
                    <th>Materia</th>
                    <th>1º Informe</th>
                    <th>2º Informe</th>
                    <th>1º Cuatrimestre</th>
                    <th>1º Informe</th>
                    <th>2º Informe</th>
                    <th>2º Cuatrimestre</th>
                    <th>Nota Final</th>
                </tr>
            </thead>
            <tbody>

                <?php

                // Lógica de color para las celdas según el valor de la nota
                function getColor($nota) {
                    if ($nota >= 7) {
                        return 'style="color: black;"';  // Nota buena
                    } elseif ($nota >= 4) {
                        return 'style="color: red;"';  // Nota baja
                    }
                }
               
                
                if ($resultadoNotas) {
                    while ($fila = $resultadoNotas->fetch_assoc()) {
                        ?>
                        <tr>
                            <td style="color: black;"><?php echo $fila['materia']; ?></td>
                            <td <?php echo getColor($fila['nota01']); ?>><?php echo $fila['nota01']; ?></td>
                            <td <?php echo getColor($fila['nota02']); ?>><?php echo $fila['nota02']; ?></td>
                            <td <?php echo getColor($fila['notaC1']); ?>><?php echo $fila['notaC1']; ?></td>
                            <td <?php echo getColor($fila['nota1']); ?>><?php echo $fila['nota1']; ?></td>
                            <td <?php echo getColor($fila['nota2']); ?>><?php echo $fila['nota2']; ?></td>
                            <td <?php echo getColor($fila['notaC2']); ?>><?php echo $fila['notaC2']; ?></td>
                            <td <?php echo getColor($fila['notaF']); ?>><?php echo $fila['notaF']; ?></td>

                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    <?php } ?>
</div>


<script>
    function toogleMenu() {
        var menu = document.querySelector('.dropdown-content');
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    }
    
    function imprimirTabla() {
    const nombreColegio = "Colegio Provincial Dr. Ernesto Guevara";
    const logoURL = "LogoS.png"; // Ruta del logo
    const nombreUsuario = "<?php echo $_SESSION['usuario']; ?>"; // Usuario que inició sesión
    const fechaHora = new Date().toLocaleString();

    // Seleccionar la tabla original
    const tablaOriginal = document.querySelector('#tablaNotas table');
    if (!tablaOriginal) {
        alert('No se encontró la tabla de notas.');
        return;
    }

    // Clonar la tabla
    const tablaClonada = tablaOriginal.cloneNode(true);

    // Generar contenido para impresión
    const contenido = `
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <img src="${logoURL}" alt="Logo" style="width: 100px; height: auto; margin-right: 20px;">
            <h1 style="font-size: 22px; color: black; margin: 0; line-height: 100px;">${nombreColegio}</h1>
        </div>
        <h2 style="text-align: center; font-size: 22px; color: black; margin: 10px 0;">Mis Notas</h2>
        <p style="color: black;"><strong>Nombre del Alumno:</strong> ${nombreUsuario}</p>
        ${tablaClonada.outerHTML}
    `;

    // Crear ventana de impresión
    const ventanaImpresion = window.open("", "_blank");
    ventanaImpresion.document.write(`
        <html>
            <head>
                <title>Imprimir Notas</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; color: black; }
                    h1, h2 { text-align: center; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; color: black; }
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
