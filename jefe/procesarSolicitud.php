<?php
include_once "../conexion.php";
include_once "../funciones.php";
include_once "../flash_messages.php";

if ($_POST) {
    // Verificar si el formulario de rechazo fue enviado
    if(isset($_POST["rechazo"])) {
        $id_rechazo = $_POST["id_rechazo"];
        $rechazo = $_POST["rechazo"];
        $motivo = $_POST["rechazo_motivo"];
        try {
            $permiso = "UPDATE registros_permisos SET permiso_aceptado = :permiso_rechazado,motivo_rechazo = :motivo_rechazo WHERE id_permisos = :id_permisos";
            //Premaramos la consulta
            $stmt = $pdo->prepare($permiso);

            //Parametros con sus valores
            $stmt->bindParam(':id_permisos',$id_rechazo,PDO::PARAM_INT);
            $stmt->bindParam(':permiso_rechazado',$rechazo,PDO::PARAM_STR);
            $stmt->bindParam(':motivo_rechazo',$motivo,PDO::PARAM_STR);

            //Ejecutamos la consulta con los parametros
            $stmt->execute();

            $stmt->execute();
            create_flash_message(
                'Permiso rechazado correctamente',
                'success'
            );

            redirect(RUTA_ABSOLUTA . "jefe/permisosSolicitados");
            // echo "Se Registro el rechazo";
        } catch (PDOException $e) {
            echo "Error de exepcion" . $e->getMessage();
        }
    }

    // Verificar si el formulario de aprobación fue enviado
    if(isset($_POST["aprobar"])) {
        $id_aprueba = $_POST["id_aprueba"];
        $aprobar = $_POST["aprobar"];
        $user = $_POST["user"];
        try {
            $permiso = "UPDATE registros_permisos SET permiso_aceptado = :permiso_aceptado,usuario_aprueba = :user WHERE id_permisos = :id_permisos ";
            //Premaramos la consulta
            $stmt = $pdo->prepare($permiso);

            //Parametros con sus valores
            $stmt->bindParam(':id_permisos',$id_aprueba,PDO::PARAM_INT);
            $stmt->bindParam(':permiso_aceptado',$aprobar,PDO::PARAM_STR);
            $stmt->bindParam(':user',$user,PDO::PARAM_STR);

            //Ejecutamos la consulta con los parametros
            $stmt->execute();
            create_flash_message(
                'Aprobacion Exitosa',
                'success'
            );

            redirect(RUTA_ABSOLUTA . "jefe/permisosSolicitados");

        } catch (PDOException $e) {
            echo "Error de exepcion" . $e->getMessage();
        }
    }

    // Verificar si el formulario de aprobación fue enviado
    if(isset($_POST["aprobar_rechazo"])) {
        $id_aprueba = $_POST["id_aprueba"];
        $aprobar_rechazo = $_POST["aprobar_rechazo"];
        $aprobarRechazo = $_POST["aprobarRechazo"];
        $user = $_POST["user"];
        try {
            $permiso = "UPDATE registros_permisos SET permiso_aceptado = :permiso_aceptado,motivo_rechazo = :motivo_rechazo,usuario_aprueba = :user WHERE id_permisos = :id_permisos ";
            //Premaramos la consulta
            $stmt = $pdo->prepare($permiso);

            //Parametros con sus valores
            $stmt->bindParam(':id_permisos',$id_aprueba,PDO::PARAM_INT);
            $stmt->bindParam(':motivo_rechazo',$aprobar_rechazo,PDO::PARAM_STR);
            $stmt->bindParam(':permiso_aceptado',$aprobarRechazo,PDO::PARAM_STR);
            $stmt->bindParam(':user',$user,PDO::PARAM_STR);

            //Ejecutamos la consulta con los parametros
            $stmt->execute();
            create_flash_message(
                'Aprobacion Exitosa',
                'success'
            );

            redirect(RUTA_ABSOLUTA . "jefe/permisosRechazados");
        } catch (PDOException $e) {
            echo "Error de exepcion" . $e->getMessage();
        }
    }

    if(isset($_POST["cancelar"])) {
        $id_cancelar = $_POST["id_cancelar"];
        $cancelar = $_POST["cancelar"];
        $user = $_POST["user"];
        try {
            $permiso = "UPDATE registros_permisos SET permiso_aceptado = :permiso_cancelado,usuario_aprueba = :user WHERE id_permisos = :id_cancelar";
            //Premaramos la consulta
            $stmt = $pdo->prepare($permiso);

            //Parametros con sus valores
            $stmt->bindParam(':id_cancelar',$id_cancelar,PDO::PARAM_INT);
            $stmt->bindParam(':permiso_cancelado',$cancelar,PDO::PARAM_STR);
            $stmt->bindParam(':user',$user,PDO::PARAM_STR);

            //Ejecutamos la consulta con los parametros
            $stmt->execute();

            create_flash_message(
                'Aprobacion Cancelada correctamente',
                'success'
            );

            redirect(RUTA_ABSOLUTA . "jefe/permisosAprobados");
            // echo "Se Cancelo la aprobacion de la solicitud ";
        } catch (PDOException $e) {
            echo "Error de exepcion" . $e->getMessage();
        }
    }
}


?>