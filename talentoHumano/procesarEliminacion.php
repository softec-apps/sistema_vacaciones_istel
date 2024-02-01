<?php
include_once "../funciones.php";
include_once "../conexion.php";


if ($_POST) {
    $id_usuario=$_POST["cliente_id"];

    $eliminar = eliminar_user($pdo,$id_usuario);

    if (strpos($eliminar, "Usuario Eliminado") !== false) {
        create_flash_message(
            'Funcionario Eliminado Exitosamente ',
            'success'
        );

        redirect(RUTA_ABSOLUTA . "talentoHumano/registrarFuncionario");
    }
}

?>