<?php
include_once "../funciones.php";
include_once "../conexion.php";


if ($_POST) {
    $id_usuario=(isset($_POST["cliente_id"])?$_POST["cliente_id"]:null);
    if (empty($id_usuario)) {
        create_flash_message(
            'Problemas con el parámetro',
            'error'
        );

        redirect(RUTA_ABSOLUTA . "admin/admin");
    }

    try {
        $eliminar = eliminar_user($pdo,$id_usuario);

        if (strpos($eliminar, "Usuario Eliminado") !== false) {
            create_flash_message(
                'Usuario eliminado correctamente ',
                'success'
            );

            redirect(RUTA_ABSOLUTA . "admin/admin");
        }
    } catch (PDOException $e) {
        create_flash_message(
            'Ocurrió un error con el sistema',
            'error'
        );
        redirect(RUTA_ABSOLUTA . "logout");
    }

}

?>