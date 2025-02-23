<?php
session_start();
// Verificar si el usuario es profesor

// Evitar que el navegador almacene en caché esta página
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['usuario'])) {
    // Redirigir si no hay sesión iniciada
    header("Location: login.php");
    exit();
}

$nombreUsuario = isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : "Desconocido";
$rolUsuario = isset($_SESSION['rol']) ? $_SESSION['rol'] : '';

// Si el rol es 'profesor', solo mostrar alumnos
if ($rolUsuario == 'profesor') {
    $rolFiltro = "AND rol = 'alumno'";
} else {
    // Si es admin, no aplicar filtro
    $rolFiltro = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="/formulario/css/usuarios.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Incluye jQuery -->
</head>
<body>
<header>
    <div class="icon__menu">
        <i class="fas fa-bars" id="btn_open"></i>
    </div>
    <h2 style="color: black;">Bienvenido, Profesor/a: <?php echo $nombreUsuario; ?></h2>
</header>

<h1 style="color:black;">Alumnos Registrados</h1>

<nav>
    <ul>
        <li><a href="#Colegio" class="container__link"><img src="LogoS.png" alt="" class="container__logo"> Colegio Provincial Dr. Ernesto Guevara</a></li>          
    </ul>
</nav>

<!-- Barra de búsqueda -->
<div class="search-container">
    <div class="search-box">
        <span class="search-icon">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAd5JREFUSEu11cvLTWEUx/HPGxnIpSiXiZAwcIuSAQpFL2XqUgxIURhQBv4AMiEhDCgk5Q+Q0VsImSAlopTIgFwi9+uz6nl1HO/e5xnssya7zn7O+j5rrd9v7R5djp4u51cHmIB1WInZ+SJ3cAkX8LzkcgMBRuAcVtck+I2L2IRPdaB2wFDcwgx8xUkcxpOcZDJ2YQuG5LOL8L0K0g44iw14hhV4UPHHWbicLjAeB7G7BDAT9/Aj9Xk+ot91sRhX8A2T8GKgw60VHMF2HMWOkgHiPNYnyF7s7wR4jCm5//cLAUvQh2vpGRX9F60VfEQMeRB+FQJGpWpf4w1GdwK8x3AMQ8BKIs5+wCuM6QS4mw01L3ngdkn2pKQFSW03c5uWdQIcSKbZgxPYVgg4kzyyMbUnBLKzE2AqHuJnoUzDYFdz0sqq2412Ktv/KZYnCT6qqGRONtrY7PatJUaLM62r4guO41B2dryPKmNVbMbgnDRmEK6PYdfKtP/lyGygVTVzeJd20b7U0rWYm12/FPH7P1G3ridml/Yids/nrK4bOIa3CB+E0WKdhwpDSeGJv9HEBycg1zEdsQ0W4mU/oQlA5AqThaKm5fmcbhoQ+cal9b4mfz8abVGtJ5tqUSWk64A/OxFWGSrrSC8AAAAASUVORK5CYII=" alt="Buscar">
        </span>
        <input type="text" id="searchInput" placeholder="Buscar por nombre, email, DNI...">
    </div>
</div>

<!-- Menú desplegable -->
<div class="dropdown">
    <button class="btn" onclick="toggleMenu()">Menú</button>
    <div class="dropdown-content">
        <button onclick="imprimirTabla()" class="btn-imprimir">Imprimir Tabla</button>
        <form action="cerrarSesion.php" method="POST">
            <button type="submit" id="btnCerrarSesion" class="btn-sesion">Cerrar Sesión</button>
        </form>
    </div>
</div>

<?php
// Conexión a la base de datos
$inc = include("conexion.php"); 
if ($inc) {
    // Filtramos por rol de alumno si es un profesor
    $consulta = "SELECT id, nombre, email, rol, DNI, curso FROM datos WHERE 1=1 $rolFiltro";
    $resultado = mysqli_query($conexion, $consulta);
    if ($resultado) {
  
        echo "<table id='tablaAlumnos' class='data-table'>"; // Inicio de la tabla
        echo "<thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>DNI</th><th>Curso</th><th>Roles</th><th>Notas</th></tr></thead>"; // Encabezados
        echo "<tbody>";
        while ($row = mysqli_fetch_array($resultado)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['DNI']) . "</td>";
            echo "<td>" . htmlspecialchars($row['curso']) . "</td>";
            echo "<td>" . htmlspecialchars($row['rol']) . "</td>";
            
            // Para los profesores, solo mostrar la columna de notas y no las de acciones
            if ($rolUsuario == 'profesor') {
               
                echo "<td><a class='btn btn-info' href='alumnosP.php?id=" . $row['id'] . "'>Ver Notas</a></td>";
            } else {
                ?>
                
                <td>
                    <a class="btn btn-info" href="alumnosP.php?id=<?php echo $row['id']; ?>">Ver Notas</a>
                </td>
                <?php
            }
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>"; // Fin de la tabla
    } else {
        echo "Error en la consulta: " . mysqli_error($conexion);
    }
} else {
    echo "Error al incluir el archivo de conexión.";
}
?>

<script>
// Filtrar la tabla con la barra de búsqueda
$(document).ready(function() {
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase(); // Obtener el valor de la barra de búsqueda

        // Filtrar las filas de la tabla
        $(".data-table tbody tr").filter(function() {
            // Obtener el texto de las columnas que deseas filtrar
            var text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(value) > -1); // Mostrar u ocultar filas que coincidan con el texto
        });
    });
});

// Función para imprimir la tabla sin la columna "Notas"
function imprimirTabla() {
    const nombreColegio = "Colegio Provincial Dr. Ernesto Guevara";
    const logoURL = "LogoS.png"; // Ruta del logo
    const nombreUsuario = "<?php echo $_SESSION['usuario']; ?>"; // Usuario que inició sesión
    const fechaHora = new Date().toLocaleString();

    // Seleccionar la tabla de alumnos por su ID
    const tablaOriginal = document.querySelector('#tablaAlumnos');
    if (!tablaOriginal) {
        alert('No se encontró la tabla de alumnos.');
        return;
    }

    // Clonar la tabla para evitar modificar la original
    const tablaClonada = tablaOriginal.cloneNode(true);

    // Eliminar la columna "Notas" del encabezado y de cada fila
    const indiceNotas = Array.from(tablaClonada.querySelectorAll('th')).findIndex(
        th => th.textContent.trim() === 'Notas'
    );

    if (indiceNotas !== -1) {
        // Eliminar la celda del encabezado
        tablaClonada.querySelectorAll('th')[indiceNotas].remove();

        // Eliminar las celdas de "Notas" en cada fila
        tablaClonada.querySelectorAll('tr').forEach(tr => {
            const celdas = tr.querySelectorAll('td, th');
            if (celdas[indiceNotas]) {
                celdas[indiceNotas].remove();
            }
        });
    }

    // Generar contenido HTML para la impresión
    const contenido = `
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <img src="${logoURL}" alt="Logo" style="width: 80px; height: auto; margin-right: 20px;">
            <h1 style="font-size: 22px; color: black; margin: 0;">${nombreColegio}</h1>
        </div>
        <h2 style="text-align: center; font-size: 22px; color: black; margin: 10px 0;">Alumnos Registrados</h2>
        <p style="color: black;"><strong>Impreso por:</strong> ${nombreUsuario}</p>
        ${tablaClonada.outerHTML}
    `;

    // Crear una ventana de impresión
    const ventanaImpresion = window.open("", "_blank");
    ventanaImpresion.document.write(`
        <html>
            <head>
                <title>Imprimir Alumnos Registrados</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; color: black; }
                    h1, h2 { text-align: center; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; color: black; }
                    th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
                    th { background-color: #f9f9f9; }
                    img { display: block; }
                    .header { display: flex; align-items: center; }
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
            <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px;">
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
