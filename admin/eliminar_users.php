<?php
include_once "../funciones.php";
include_once "../conexion.php";


if ($_POST) {
    $id_usuario=$_POST["cliente_id"];

    eliminar_user($pdo,$id_usuario);
}

?>