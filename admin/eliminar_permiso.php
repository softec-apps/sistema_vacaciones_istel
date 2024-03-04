<?php
include_once "../funciones.php";
include_once "../conexion.php";


if ($_POST) {
    $id_permisos=$_POST["id_permiso"];

    try {
        $eliminar = eliminar_permiso($pdo,$id_permisos );
        if ($eliminar) {
            create_flash_message(
                'Permiso Eliminado Correctamente',
                'success'
            );

            redirect(RUTA_ABSOLUTA . "admin/solicitud_general");
        }else {
            create_flash_message(
                'El permiso no se puede eliminar',
                'error'
            );
            redirect(RUTA_ABSOLUTA . "admin/solicitud_general");
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