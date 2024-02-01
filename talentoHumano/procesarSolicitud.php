<?php
include_once "../conexion.php";
include_once "../funciones.php";
include_once "../flash_messages.php";

if ($_POST) {

    // Verificar si el formulario de registro fue enviado
    if(isset($_POST["registrar"])) {
        $id_registrar = $_POST["id_registrar"];
        $registrar = $_POST["registrar"];
        $user = $_POST["user"];
        try {
            $permiso = "UPDATE registros_permisos SET permiso_aceptado = :permiso_aceptado,usuario_registra = :user WHERE id_permisos = :id_permisos";
            //Premaramos la consulta
            $stmt = $pdo->prepare($permiso);

            //Parametros con sus valores
            $stmt->bindParam(':id_permisos',$id_registrar,PDO::PARAM_INT);
            $stmt->bindParam(':permiso_aceptado',$registrar,PDO::PARAM_STR);
            $stmt->bindParam(':user',$user,PDO::PARAM_STR);

            //Ejecutamos la consulta con los parametros
            $stmt->execute();
            create_flash_message(
                'Permiso Registrado Correctamente',
                'success'
            );

            redirect(RUTA_ABSOLUTA . "talentoHumano/permisosAprobados");

        } catch (PDOException $e) {
            echo "Error de exepcion" . $e->getMessage();
        }
    }
    if(isset($_POST["cancelar_registro"])) {
        $id_cancelar = $_POST["id_cancelar"];
        $cancelar_registro = $_POST["cancelar_registro"];
        $user = $_POST["user"];
        try {
            $permiso = "UPDATE registros_permisos SET permiso_aceptado = :permiso_cancelado,usuario_registra = :user WHERE id_permisos = :id_cancelar";
            //Premaramos la consulta
            $stmt = $pdo->prepare($permiso);

            //Parametros con sus valores
            $stmt->bindParam(':id_cancelar',$id_cancelar,PDO::PARAM_INT);
            $stmt->bindParam(':permiso_cancelado',$cancelar_registro,PDO::PARAM_STR);
            $stmt->bindParam(':user',$user,PDO::PARAM_STR);

            //Ejecutamos la consulta con los parametros
            $stmt->execute();
            create_flash_message(
                'Se Cancelo el Registro Correctamente',
                'success'
            );

            redirect(RUTA_ABSOLUTA . "talentoHumano/permisosRegistrados");

        } catch (PDOException $e) {
            echo "Error de exepcion" . $e->getMessage();
        }
    }
}


?>