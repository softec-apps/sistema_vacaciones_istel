<?php
include_once "../funciones.php";
include_once "../conexion.php";


if ($_POST) {
    $id_usuarios=$_POST["cliente_id"];
    $cedula =$_POST["cedula"];
    $nombres =$_POST["nombres"];
    $apellidos =$_POST["apellidos"];
    $email =$_POST["email_A"];
    $rol =$_POST["roles"];
    $fecha_ingreso = $_POST["fecha_ingreso"];
    $tiempo_trabajo = isset($_POST["tiempo_trabajo"]) ? $_POST["tiempo_trabajo"] : 0;


    $act = actualizar_usuario($pdo,$id_usuarios,$cedula,$nombres,$apellidos,$email,$rol,$fecha_ingreso,$tiempo_trabajo);

    if (strpos($act, "Datos Actualizados") !== false) {
        create_flash_message(
            'Datos Actualizados Correctamente ',
            'success'
        );

        redirect(RUTA_ABSOLUTA . "admin/register");
    }
}

?>