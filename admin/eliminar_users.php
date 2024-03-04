<?php
include_once "../funciones.php";
include_once "../conexion.php";


if ($_POST) {
    $id_usuario=$_POST["cliente_id"];

    try {
        $eliminar = eliminar_user($pdo,$id_usuario);

        if (strpos($eliminar, "Usuario Eliminado") !== false) {
            create_flash_message(
                'Usuario Eliminado Correctamente ',
                'success'
            );

            redirect(RUTA_ABSOLUTA . "admin/admin");
        }
    } catch (PDOException $e) {
        create_flash_message(
            'Ocurrio un error con el sistema',
            'error'
        );
        redirect(RUTA_ABSOLUTA . "logout");
    }

}

?>