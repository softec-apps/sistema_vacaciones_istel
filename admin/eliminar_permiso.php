<?php
include_once "../funciones.php";
include_once "../conexion.php";


if ($_POST) {
    $id_permisos=$_POST["id_permiso"];

    eliminar_permiso($pdo,$id_permisos );
}

?>