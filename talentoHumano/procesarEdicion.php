<?php
include_once "../funciones.php";
include_once "../conexion.php";


if ($_POST) {
    $id_usuarios=$_POST["cliente_id"];
    $cedula =$_POST["cedula"];
    $nombres =$_POST["nombres"];
    $apellidos =$_POST["apellidos"];
    $user_A =$_POST["user_A"];
    $password_A =$_POST["password_A"];
    $email =$_POST["email_A"];
    $rol =$_POST["roles"];
    $fecha_ingreso = $_POST["fecha_ingreso"];
    $tiempo_trabajo = isset($_POST["tiempo_trabajo"]) ? $_POST["tiempo_trabajo"] : 0;

try {

    $act = actualizar_usuario($pdo,$id_usuarios,$cedula,$nombres,$apellidos,$user_A,$password_A,$email,$rol,$fecha_ingreso,$tiempo_trabajo);

    if (strpos($act, "Datos Actualizados") !== false) {
        create_flash_message(
            'Datos Actualizados Correctamente ',
            'success'
        );
        redirect(RUTA_ABSOLUTA . "talentoHumano/registrarFuncionario");

    }elseif (strpos($act, "Error: Violación de clave única.") !== false) {
        create_flash_message(
            'Error: Cédula, correo o usuario duplicado.',
            'error'
        );

        redirect(RUTA_ABSOLUTA . "talentoHumano/registrarFuncionario");

    }else {
        create_flash_message(
            'Datos No Actualizados ',
            'error'
        );

        redirect(RUTA_ABSOLUTA . "talentoHumano/registrarFuncionario");
    }
} catch (PDOException $e) {

    redirect(RUTA_ABSOLUTA . "logout");
}
}

?>