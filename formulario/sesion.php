<?php
class conexionB {
    public static function conexionBD() {
        include("conexion.php");
        try {
            $conn = new PDO("mysql:host=$server; port=3306;dbname=$db", $user);
            //echo "Se conectó a la base de datos"; // Agregado el punto y coma aquí
        } catch (PDOException $pe) {
            die("No se logró conectar a la base de datos: " . $pe->getMessage());
        }
        return $conn;
    }

    public static function CerrarConexion() {
        $conex = conexion::conexionBD()->close();
        return $conex;
    }
}
?>
