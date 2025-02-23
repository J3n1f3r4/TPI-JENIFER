<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("conexion.php");

if (isset($_POST['accion'])) { 
    switch ($_POST['accion']) {
        case 'editar_registro':
            editar_registro();
            break; 
            
        case 'editar_user':
            editar_user();
            break; 
    
        case 'eliminar_registro':
            eliminar_registro();
            break;

        case 'acceso_user':
            acceso_user();
            break;
    }
}

function editar_registro() {
    $conexion = mysqli_connect("localhost", "root", "", "formulario");
    extract($_POST);

    $consulta = "UPDATE alumno1 SET 
        materia = '$materia', 
        nota01 = '$nota01', 
        nota02 = '$nota02', 
        notaC1 = '$notaC1', 
        nota1 = '$nota1',
        nota2 = '$nota2', 
        notaC2 = '$notaC2', 
        notaF = '$notaF' 
        WHERE id = '$id'";

    mysqli_query($conexion, $consulta);
    header('Location: departamento.php');
}

function editar_user() {
    $conexion = mysqli_connect("localhost", "root", "", "formulario");
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }
    

    // Verificar que los datos llegaron correctamente
    if (!isset($_POST['nombre'], $_POST['correo'], $_POST['DNI'], $_POST['curso'], $_POST['rol'], $_POST['id'])) {
        die('Faltan datos en el formulario');
    }

    extract($_POST);

    // Verificar los datos que llegaron
    var_dump($_POST);  // Verifica los valores que están siendo enviados.

    // Validación básica de entrada
    $nombre = mysqli_real_escape_string($conexion, $nombre);
    $correo = mysqli_real_escape_string($conexion, $correo);
    $DNI = intval($DNI);
    $curso = mysqli_real_escape_string($conexion, $curso);
    $rol = intval($rol);
    $id = intval($id);

    // Verifica los valores después de ser procesados
    echo "Datos procesados: nombre=$nombre, correo=$correo, DNI=$DNI, curso=$curso, rol=$rol, id=$id";

    // Consulta de actualización
    $consulta = "UPDATE datos SET nombre = '$nombre', correo = '$correo', DNI = '$DNI', curso = '$curso', rol = '$rol' WHERE id = '$id'";

    // Verificar la consulta antes de ejecutarla
    echo "Consulta SQL: $consulta";

    if (mysqli_query($conexion, $consulta)) {
        // Asegúrate de agregar exit() para evitar la continuación del código.
        header("Location: usuarios.php");
        exit();  // Salir después de la redirección
    } else {
        // Si hay error, mostrar el mensaje
        echo "Error al actualizar el registro: " . mysqli_error($conexion);
    }
}




function eliminar_registro() {
    $conexion = mysqli_connect("localhost", "root", "", "formulario");
    extract($_POST);

    $consulta = "DELETE FROM datos WHERE id = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: usuarios.php');
    } else {
        echo "Error al eliminar el registro: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
}

function acceso_user() {
    session_start();
    $conexion = mysqli_connect("localhost", "root", "", "formulario");

    $nombre = $_POST['nombre'];
    $password = $_POST['password'];

    $_SESSION['nombre'] = $nombre;

    $consulta = "SELECT * FROM datos WHERE nombre = ? AND password = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("ss", $nombre, $password);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        if ($fila['rol'] == 1) {
            header('Location: departamento.php');
        } elseif ($fila['rol'] == 2) {
            header('Location: alumno.php');
        }
    } else {
        session_destroy();
        header('Location: r.php');
    }

    $stmt->close();
    $conexion->close();
}
