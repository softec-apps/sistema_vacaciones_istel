<?php
include_once "funciones.php";
include_once "conexion.php";


if ($_POST) {
    $usuario =$_POST["usuario"];
    $clave = $_POST["clave"];


    star_sesion($usuario,$clave,$pdo);
}

?>