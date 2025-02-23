<?php
include "conexion.php";
if(isset($_POST['registrar'])){

    if(strlen($_POST['materia']) >= 1
    && strlen($_POST['nota01']) >= 1 && strlen($_POST['nota02']) >= 1 && strlen($_POST['notaC1']) >= 1 
    && strlen($_POST['nota1']) >= 1 && strlen($_POST['nota2']) >= 1 && strlen($_POST['notaC2']) >= 1
    && strlen($_POST['notaF']) >= 1){

     $materia = trim($_POST['materia']);
     $nota01= trim($_POST['nota01']);
     $nota02 = trim($_POST['nota02']);
     $notaC1 = trim($_POST['notaC1']);
     $nota1 = trim($_POST['nota1']);
     $nota2 = trim($_POST['nota2']);
     $notaC2 = trim($_POST['notaC2']);
     $notaF = trim($_POST['notaF']);

     $consulta= "INSERT INTO alumno1 ( materia, nota01, nota02, notaC1, nota1, nota2, notaC2, notaF)
    VALUES ('$materia', '$nota01', '$nota02', '$notaC1', '$nota1', '$nota2', '$notaC2', '$notaF' )";

mysqli_query($conexion, $consulta);
mysqli_close($conexion);

header('Location: departamento.php');

    }}
?>