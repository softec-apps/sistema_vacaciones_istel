<?php
include_once "funciones.php";
include_once "conexion.php";
include_once "redirection.php";


if ($_POST) {

$id_usuarios = $_POST['id_usuario'];


calcular_actualizar($pdo);
redirect("admin/trabajo");


}

?>