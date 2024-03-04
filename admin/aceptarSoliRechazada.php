<?php
include_once "../conexion.php";
include_once "../funciones.php";
include_once "../flash_messages.php";

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
            'Permiso Aprobado Exitosamente',
            'success'
        );

        redirect(RUTA_ABSOLUTA . "permisos/rechazados");
    } catch (PDOException $e) {
        create_flash_message(
            'Ocurrio un error con el sistema',
            'error'
        );
        redirect(RUTA_ABSOLUTA . "logout");
        // echo "Error de exepcion" . $e->getMessage();
    }
}
?>