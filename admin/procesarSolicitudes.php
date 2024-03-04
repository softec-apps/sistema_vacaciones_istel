<?php
include_once "../conexion.php";
include_once "../funciones.php";
include_once "../flash_messages.php";

if ($_POST) {
    $anioMesArray = obtenerAnioMesActual();
    $anio = $anioMesArray['anio'];
    $mes = $anioMesArray['mes'];
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

            create_flash_message(
                'Permiso Rechazado Correctamente',
                'success'
            );

            redirect(RUTA_ABSOLUTA . "admin/permisos_pendientes");
        } catch (PDOException $e) {
            create_flash_message(
                'Ocurrió un error con el sistema',
                'error'
            );
            redirect(RUTA_ABSOLUTA . "logout");
            // echo "Error de exepcion" . $e->getMessage();
        }
    }

    // Verificar si el formulario de aprobación fue enviado
    if (isset($_POST["aprobar"])) {

        // Obtener la ruta del script actual
        $baseDir = __DIR__;

        // Construir rutas relativas basadas en $baseDir
        $directorioBase = realpath($baseDir . '/..') . "/htArchivos";

        $directorioAnio = $directorioBase . '/' . $anio;
        $directorioMes = $directorioAnio . '/' . $mes;

        $id_aprueba = $_POST["id_aprueba"];
        $id_user = $_POST["id_user"];
        $aprobar = $_POST["aprobar"];
        if ($aprobar != 1) {
            create_flash_message('Ocurrió un error de valores','error');
            redirect(RUTA_ABSOLUTA . "admin/permisos_pendientes");

        }
        $user = $_POST["user"];
        $asistir = $_POST["asistirAprobacion"];
        if (empty($asistir)) {
            $asistir = "Admin";
        }
        $archivoDescripcion = "Asistido por: " . $asistir . " " . $_POST["archivoDescripcion"];
        $archivoAprueba = $_FILES["archivoAprueba"];

        $nombreOriginal = $archivoAprueba['name'];
        $file_tmp = $archivoAprueba['tmp_name'];

        $nombreSinEspacios = str_replace(' ', '_', quitarAcentos($nombreOriginal));
        $extension = pathinfo($nombreSinEspacios, PATHINFO_EXTENSION);

        $nombreUnico = date("d") . date("His") . date("m") . '.' . $extension;

        $route = $directorioMes . '/' . $nombreUnico;
        $file_tmp = $file_tmp;

        try {
            // Iniciar la transacción
            $pdo->beginTransaction();

            if (!file_exists($directorioBase)) {
                mkdir($directorioBase, 0777, true);
            }

            if (!file_exists($directorioAnio)) {
                mkdir($directorioAnio, 0777, true);
            }

            if (!file_exists($directorioMes)) {
                mkdir($directorioMes, 0777, true);
            }

            if (strtolower($extension) != 'pdf') {
                throw new Exception("Solo se permiten archivos PDF.");
            } elseif (move_uploaded_file($file_tmp, $route)) {

                // Construir la ruta relativa basada en $baseDir
                $routeRelativeToScript = str_replace($baseDir, '', $route);

                // Asignar la nueva ruta relativa
                $route = $routeRelativeToScript;

                $permiso = "UPDATE registros_permisos SET permiso_aceptado = :permiso_aceptado, usuario_aprueba = :user, ruta_aprueba = :ruta_aprueba WHERE id_permisos = :id_permisos";
                $stmt = $pdo->prepare($permiso);

                $stmt->bindParam(':id_permisos', $id_aprueba, PDO::PARAM_INT);
                $stmt->bindParam(':permiso_aceptado', $aprobar, PDO::PARAM_STR);
                $stmt->bindParam(':user', $user, PDO::PARAM_STR);
                $stmt->bindParam(':ruta_aprueba', $route, PDO::PARAM_STR);

                $stmt->execute();

                $insertArchivos = "UPDATE archivos SET descripcion_aprueba = :descripcion_aprueba,ruta_aprueba = :ruta_aprueba,id_aprueba = :id_user WHERE id_permiso = :id_permiso";

                $stmtArchivos = $pdo->prepare($insertArchivos);

                $stmtArchivos->bindParam(':id_permiso', $id_aprueba, PDO::PARAM_INT);
                $stmtArchivos->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                $stmtArchivos->bindParam(':descripcion_aprueba', $archivoDescripcion, PDO::PARAM_STR);
                $stmtArchivos->bindParam(':ruta_aprueba', $route, PDO::PARAM_STR);

                $stmtArchivos->execute();

                // Confirmar la transacción
                $pdo->commit();

                create_flash_message('Se ejecutó la aprobación', 'success');
                redirect(RUTA_ABSOLUTA . "admin/permisos_pendientes");
            } else {
                throw new Exception("Error: El archivo no se pudo guardar.");
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            create_flash_message('Hubo un error en la base de datos', 'error');
            redirect(RUTA_ABSOLUTA . "admin/permisos_pendientes");

        }catch (Exception $e) {
            create_flash_message($e->getMessage(), 'error');
            redirect(RUTA_ABSOLUTA . "admin/permisos_pendientes");

        }finally {

            $pdo = null;
        }
    }

    // Verificar si el formulario de aprobación fue enviado
    if(isset($_POST["aprobar_rechazo"])) {
        $id_aprueba = $_POST["id_aprueba"];
        $aprobar_rechazo = $_POST["aprobar_rechazo"];
        $aprobarRechazo = $_POST["aprobarRechazo"];
        if ($aprobarRechazo != 0) {
            create_flash_message('Ocurrió un error de valores','error');
            redirect(RUTA_ABSOLUTA . "permisos/rechazados");
        }
        if (!empty($aprobar_rechazo)) {
            create_flash_message('Ocurrió un error de valores', 'error');
            redirect(RUTA_ABSOLUTA . "permisos/rechazados");
        }
        $user = "";
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
                'Permiso cancelado exitosamente',
                'success'
            );

            redirect(RUTA_ABSOLUTA . "permisos/rechazados");
        } catch (PDOException $e) {
            create_flash_message(
                'Ocurrió un error con el sistema',
                'error'
            );
            redirect(RUTA_ABSOLUTA . "permisos/rechazados");
        }
    }

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

            echo "Se actualizo la Aprobacion del registro ";
        } catch (PDOException $e) {
            create_flash_message(
                'Ocurrió un error con el sistema',
                'error'
            );
            redirect(RUTA_ABSOLUTA . "logout");
            // echo "Error de exepcion" . $e->getMessage();
        }
    }

    if(isset($_POST["cancelar"])) {
        $id_cancelar = $_POST["id_cancelar"];
        $cancelar = $_POST["cancelar"];
        $user = $_POST["user"];

        if ($cancelar != 0) {
            create_flash_message(
                'Existe un error por favor inténtelo mas tarde',
                'error'
            );

            redirect(RUTA_ABSOLUTA . "permisos/aceptados");
        }

        try {
            // Iniciar la transacción
            $pdo->beginTransaction();
            $select = "SELECT descripcion_aprueba,ruta_aprueba FROM archivos WHERE id_permiso = :id_permiso";
            $statement = $pdo->prepare($select);
            $statement->bindParam(':id_permiso', $id_cancelar, PDO::PARAM_INT);
            $statement->execute();

            $resultado = $statement->fetch(PDO::FETCH_ASSOC);
            $descripcion_aprueba = $resultado['descripcion_aprueba'];
            $ruta_aprueba = $resultado['ruta_aprueba'];

            $ruta_aprueba = str_replace('/', DIRECTORY_SEPARATOR, $ruta_aprueba);

            if (unlink($ruta_aprueba)) {
                $ruta_aprueba = null;
                $descripcion_aprueba = null;
            } else {
                $ruta_aprueba = $ruta_aprueba;
                $descripcion_aprueba = $descripcion_aprueba;

                throw new Exception("Hubo un error con el archivo");
            }

            $permiso = "UPDATE registros_permisos SET permiso_aceptado = :permiso_cancelado,usuario_aprueba = :user, ruta_aprueba=:ruta_aprueba WHERE id_permisos = :id_cancelar";
            //Premaramos la consulta
            $stmt = $pdo->prepare($permiso);

            //Parametros con sus valores
            $stmt->bindParam(':id_cancelar',$id_cancelar,PDO::PARAM_INT);
            $stmt->bindParam(':permiso_cancelado',$cancelar,PDO::PARAM_STR);
            $stmt->bindParam(':user',$user,PDO::PARAM_STR);
            $stmt->bindParam(':ruta_aprueba',$ruta_aprueba,PDO::PARAM_STR);

            //Ejecutamos la consulta con los parametros
            $stmt->execute();

            $act = "UPDATE archivos SET descripcion_aprueba = :descripcion_aprueba ,ruta_aprueba=:ruta_aprueba WHERE id_permiso = :id_permiso";

            $stmt = $pdo->prepare($act);

            $stmt->bindParam(':id_permiso',$id_cancelar,PDO::PARAM_INT);
            $stmt->bindParam(':descripcion_aprueba',$descripcion_aprueba,PDO::PARAM_STR);
            $stmt->bindParam(':ruta_aprueba',$ruta_aprueba,PDO::PARAM_STR);

            $stmt->execute();

            // Confirmar la transacción
            $pdo->commit();

            create_flash_message(
                'Se cancelo la aprobación',
                'success'
            );

            redirect(RUTA_ABSOLUTA . "permisos/aceptados");
        } catch (PDOException $e) {

            $pdo->rollBack();

            create_flash_message('Hubo un error en la base de datos', 'error');
            redirect(RUTA_ABSOLUTA . "solicitud_general");
        }catch (Exception $e) {

            create_flash_message($e->getMessage(), 'error');
            redirect(RUTA_ABSOLUTA . "permisos/aceptados");

        } finally {

            $pdo = null;
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

            echo "Se Cancelo el registro de la solicitud ";
        } catch (PDOException $e) {
            create_flash_message(
                'Ocurrió un error con el sistema',
                'error'
            );
            redirect(RUTA_ABSOLUTA . "logout");
            // echo "Error de exepcion" . $e->getMessage();
        }
    }
}


?>