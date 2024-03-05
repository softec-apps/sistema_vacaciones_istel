<?php
include_once "../funciones.php";
include_once "../conexion.php";


if ($_POST) {
    $id_usuarios=(isset($_POST["user_id"]))?$_POST["user_id"]:null;
    $recuperarClave =(isset($_POST["recuperarClave"]))?$_POST["recuperarClave"]:null;
    $repetirClave =(isset($_POST["repetirClave"]))?$_POST["repetirClave"]:null;
    if (empty($recuperarClave)) {
        create_flash_message(
            'La contraseña es obligatoria',
            'error'
        );

        redirect(RUTA_ABSOLUTA . "admin/admin");
    }
    if (empty($repetirClave)) {
        create_flash_message(
            'Debes confirmar la contraseña',
            'error'
        );

        redirect(RUTA_ABSOLUTA . "admin/admin");
    }elseif ($recuperarClave != $repetirClave) {
        create_flash_message(
            'Las contraseñas no coinciden',
            'error'
        );

        redirect(RUTA_ABSOLUTA . "admin/admin");
    }

    $act = recuperarClave($pdo,$id_usuarios,$clave);

    if (strpos($act, "Datos Actualizados") !== false) {
        create_flash_message(
            'Nueva contraseña ingresada',
            'success'
        );

        redirect(RUTA_ABSOLUTA . "admin/admin");

    }else {
        create_flash_message(
            'No se pudo ingresar la nueva contraseña',
            'error'
        );

        redirect(RUTA_ABSOLUTA . "admin/admin");
    }
}

?>