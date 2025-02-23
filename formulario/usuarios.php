<!-- Este tiene la lista de usuarios completa sola para admin -->
<?php
session_start();
// Evitar que el navegador almacene en caché esta página
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin') {
    // Redirigir si no es administrador o no hay sesión iniciada
    header("Location: r.php");
    exit();
}

$nombreUsuario = isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : "Desconocido";





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
    <h2 style="color: white;">Bienvenido, Administrador: <?php echo $nombreUsuario; ?></h2>
</header>
<h1 style="color:white;">Usuarios Registrados</h1>

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
    $consulta = "SELECT id, nombre, email, rol, DNI, curso FROM datos";
    $resultado = mysqli_query($conexion, $consulta);
    if ($resultado) {
  
        echo "<table class='data-table'>"; // Inicio de la tabla
        echo "<thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>DNI</th><th>Curso</th><th>Roles</th><th>Acciones</th><th>Notas</th></tr></thead>"; // Encabezados
        echo "<tbody>";
        while ($row = mysqli_fetch_array($resultado)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['DNI']) . "</td>";
            echo "<td>" . htmlspecialchars($row['curso']) . "</td>";
            echo "<td>" . htmlspecialchars($row['rol']) . "</td>";
            
            ?>
            <td>
            <div class="action-buttons">
            <a class="btn btn-success" href="editar_user.php?id=<?php echo $row['id']; ?>">Modificar</a>
                <button class="btn btn-danger deleteBtn" data-id="<?php echo $row['id']; ?>">Eliminar</button>
            </div>
            </td>
            
            <td>
    <?php if (strtolower($row['rol']) == 'alumno') { ?>
        <a class="btn btn-info" href="departamento.php?id=<?php echo $row['id']; ?>">Ver Notas</a>
<!-- <?php echo $row['id']; // Esto te ayudará a verificar si el ID es válido ?> -->

    <?php } ?>
</td>

            </td>

                
            </td>


            <?php
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
$(document).ready(function() {
    // Filtrar la tabla con la barra de búsqueda
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase(); // Obtener el valor de la barra de búsqueda

        // Filtrar las filas de la tabla
        $(".data-table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1); // Mostrar u ocultar filas
        });
    });

    // Eliminar usuario
    $(".deleteBtn").click(function() {
        var userId = $(this).data('id'); // Obtener el ID del usuario a eliminar
        
        // Muestra una ventana de confirmación
        var confirmation = confirm("¿Estás seguro de que quieres eliminar este registro?");
        
        if (confirmation) {
            // Realiza una petición AJAX para eliminar el usuario sin recargar la página
            $.ajax({
                url: 'eliminar_user.php', // El archivo que procesará la eliminación
                type: 'POST',
                data: { id: userId }, // Enviar el ID del usuario a eliminar
                success: function(response) {
                    if (response == 'success') {
                        alert('Usuario eliminado exitosamente.');
                        location.reload(); // Recargar la página para reflejar los cambios
                    } else {
                        alert('Hubo un error al eliminar el usuario.');
                    }
                }
            });
        }
    });
});
function imprimirTabla() {
    const nombreColegio = "Colegio Provincial Dr. Ernesto Guevara";
    const logoURL = "LogoS.png"; // Ruta del logo
    const nombreUsuario = "<?php echo $nombreUsuario; ?>"; // Usuario que inició sesión
    const fechaHora = new Date().toLocaleString();

    // Clonar la tabla y ocultar columnas de acciones y notas
    const tablaOriginal = document.querySelector('.data-table');
    const tablaClonada = tablaOriginal.cloneNode(true);

    // Ocultar las columnas de acciones y notas
    const indicesOcultar = [6, 7]; // Índices de las columnas de acciones y notas
    tablaClonada.querySelectorAll('tr').forEach((fila) => {
        indicesOcultar.forEach((indice) => {
            const celda = fila.children[indice];
            if (celda) {
                celda.style.display = 'none';
            }
        });
    });

    // Generar contenido para impresión
    const contenido = `
        <div style="display: flex; align-items: center; justify-content: flex-start; margin-bottom: 20px;">
            <img src="${logoURL}" alt="Logo" style="width: 100px; margin-right: 20px;">
            <h1 style="font-size: 20px; margin: 0;">${nombreColegio}</h1>
        </div>
        <h2 style="text-align: center; font-size: 20px ; margin: 10px 0;">Usuarios Registrados</h2>
        <p><strong>Nombre del Usuario:</strong> ${nombreUsuario}</p>
        <br>
        ${tablaClonada.outerHTML}
    `;

    // Abrir ventana de impresión
    const ventanaImpresion = window.open("", "_blank");
    ventanaImpresion.document.write(`
        <html>
            <head>
                <title>Registro de Usuarios</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    h1 { text-align: left; }
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
