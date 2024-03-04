<?php
include_once "conexion.php";
include_once "funciones.php";
// include_once "funciones_solicitudes.php";
include_once "flash_messages.php";

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


            echo "Se Registro el rechazo";
        } catch (PDOException $e) {

            redirect(RUTA_ABSOLUTA . "logout");
            // echo "Error de exepcion" . $e->getMessage();
        }
    }

    // Verificar si el formulario de aprobación fue enviado
    if (isset($_POST["aprobar"])) {
        // Obtener la ruta del script actual
        $baseDir = __DIR__;

        // Construir rutas relativas basadas en $baseDir
        $directorioBase = realpath($baseDir) . "/htArchivos";

        $directorioAnio = $directorioBase . '/' . $anio;
        $directorioMes = $directorioAnio . '/' . $mes;

        $id_aprueba = $_POST["id_aprueba"];
        $aprobar = $_POST["aprobar"];
        if ($aprobar != 1) {
            create_flash_message(
                'Existe un error por favor inténtelo mas tarde',
                'error'
            );

            redirect(RUTA_ABSOLUTA . "admin/solicitud_general");
        }
        $user = $_POST["user"];
        $archivoDescripcion = $_POST["archivoDescripcion"];
        $archivoAprueba = $_FILES["archivoAprueba"];

        $nombreOriginal = $archivoAprueba['name'];
        $file_tmp = $archivoAprueba['tmp_name'];

        $nombreSinEspacios = str_replace(' ', '_', quitarAcentos($nombreOriginal));
        $extension = pathinfo($nombreSinEspacios, PATHINFO_EXTENSION);

        $nombreUnico = uniqid() . '_' . $id_aprueba . '.' . $extension;
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

                $permiso = "UPDATE registros_permisos SET permiso_aceptado = :permiso_aceptado, usuario_aprueba = :user, ruta_aprueba = :ruta_aprueba WHERE id_permisos = :id_permisos";
                $stmt = $pdo->prepare($permiso);

                $stmt->bindParam(':id_permisos', $id_aprueba, PDO::PARAM_INT);
                $stmt->bindParam(':permiso_aceptado', $aprobar, PDO::PARAM_STR);
                $stmt->bindParam(':user', $user, PDO::PARAM_STR);
                $stmt->bindParam(':ruta_aprueba', $route, PDO::PARAM_STR);

                $stmt->execute();

                $insertArchivos = "UPDATE archivos SET descripcion_aprueba = :descripcion_aprueba,ruta_aprueba = :ruta_aprueba WHERE id_permiso = :id_permiso";

                $stmtArchivos = $pdo->prepare($insertArchivos);

                $stmtArchivos->bindParam(':id_permiso', $id_aprueba, PDO::PARAM_INT);
                $stmtArchivos->bindParam(':descripcion_aprueba', $archivoDescripcion, PDO::PARAM_STR);
                $stmtArchivos->bindParam(':ruta_aprueba', $route, PDO::PARAM_STR);

                $stmtArchivos->execute();

                // Confirmar la transacción
                $pdo->commit();

                create_flash_message('Se ejecutó la aprobación', 'success');
                redirect(RUTA_ABSOLUTA . "admin/solicitud_general");
            } else {
                throw new Exception("Error: El archivo no se pudo guardar.");
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            create_flash_message('Ocurrió un error en la base de datos', 'error');
            redirect(RUTA_ABSOLUTA . "admin/solicitud_general");
            // echo "Ocurrió un error" . $e->getMessage();
        } catch (Exception $e) {
            create_flash_message($e->getMessage(), 'error');
            redirect(RUTA_ABSOLUTA . "admin/solicitud_general");
            // echo "Error: " . $e->getMessage();
        } finally {

            $pdo = null;
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

            echo "Se Ejecuto la Nueva aprovacion";
        } catch (PDOException $e) {

            redirect(RUTA_ABSOLUTA . "logout");
            // echo "Error de exepcion" . $e->getMessage();
        }
    }

    // Verificar si el formulario de registro fue enviado
    if(isset($_POST["registrar"])) {
        $id_registrar = $_POST["id_registrar"];
        $id_user = $_POST["id_user"];
        $registrar = $_POST["registrar"];
        $user = $_POST["user"];
        $asisteRegistra = $_POST["asisteRegistra"];
        if (empty($asisteRegistra)) {
            $asisteRegistra = "Admin";
        }else {
            $asisteRegistra = $asisteRegistra;
        }
        $archivoDescripcion = "Asistido por: " . $asisteRegistra . " " . $_POST["archivoDescripcion"];
        $archivoRegistra = $_FILES["archivoRegistra"];

        if ($registrar!=3) {
            create_flash_message('Ocurrió un error con el valor de la solicitud','error');
            redirect(RUTA_ABSOLUTA . "admin/permisos");
        }


        // Obtener la ruta del script actual
        $baseDir = __DIR__;

        // Construir rutas relativas basadas en $baseDir
        $directorioBase = realpath($baseDir) . "/htArchivos";

        $directorioAnio = $directorioBase . '/' . $anio;
        $directorioMes = $directorioAnio . '/' . $mes;

        $nombreOriginal = $archivoRegistra['name'];
        $file_tmp = $archivoRegistra['tmp_name'];

        $nombreSinEspacios = str_replace(' ', '_', quitarAcentos($nombreOriginal));
        $extension = pathinfo($nombreSinEspacios, PATHINFO_EXTENSION);

        $nombreUnico = uniqid() . '_' . $id_aprueba . '.' . $extension;
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

                $permiso = "UPDATE registros_permisos SET permiso_aceptado = :permiso_aceptado,usuario_registra = :user,ruta_registra = :ruta_registra WHERE id_permisos = :id_permisos";

                $stmt = $pdo->prepare($permiso);

                $stmt->bindParam(':id_permisos',$id_registrar,PDO::PARAM_INT);
                $stmt->bindParam(':permiso_aceptado',$registrar,PDO::PARAM_STR);
                $stmt->bindParam(':user',$user,PDO::PARAM_STR);
                $stmt->bindParam(':ruta_registra',$route,PDO::PARAM_STR);

                //Ejecutamos la consulta con los parametros
                $stmt->execute();

                $insertArchivos = "UPDATE archivos SET descripcion_registra = :descripcion_registra,ruta_registra = :ruta_registra, id_registra =:id_user WHERE id_permiso = :id_permiso";
                //  htArchivos/2024/02/65ce9a206e23b_.pdf
                $stmtArchivos = $pdo->prepare($insertArchivos);

                $stmtArchivos->bindParam(':id_permiso', $id_registrar, PDO::PARAM_INT);
                $stmtArchivos->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                $stmtArchivos->bindParam(':descripcion_registra', $archivoDescripcion, PDO::PARAM_STR);
                $stmtArchivos->bindParam(':ruta_registra', $route, PDO::PARAM_STR);

                $stmtArchivos->execute();

                // Confirmar la transacción
                $pdo->commit();

                create_flash_message('Permiso registrado correctamente','success');
                redirect(RUTA_ABSOLUTA . "admin/permisos");
            } else {
                throw new Exception("Error: El archivo no se pudo guardar.");
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            create_flash_message('Ocurrió un error en la base de datos', 'error');
            redirect(RUTA_ABSOLUTA . "admin/permisos");
        } catch (Exception $e) {
            $pdo->rollBack();
            create_flash_message($e->getMessage(), 'error');
            redirect(RUTA_ABSOLUTA . "admin/permisos");
        } finally {

            $pdo = null;
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

            redirect(RUTA_ABSOLUTA . "admin/solicitud_general");
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

            redirect(RUTA_ABSOLUTA . "admin/solicitud_general");
        } catch (PDOException $e) {

            $pdo->rollBack();

            create_flash_message('Hubo un error en la base de datos', 'error');
            redirect(RUTA_ABSOLUTA . "solicitud_general");
        }catch (Exception $e) {

            create_flash_message($e->getMessage(), 'error');
            redirect(RUTA_ABSOLUTA . "admin/solicitud_general");

        } finally {

            $pdo = null;
        }
    }


    if(isset($_POST["cancelar_registro"])) {
        $id_cancelar = $_POST["id_cancelar"];
        $cancelar_registro = $_POST["cancelar_registro"];
        $user = "";
        $id_registra = "";
        if ($cancelar_registro != 1) {

            create_flash_message('Existe un error por favor inténtelo mas tarde','error');
            redirect(RUTA_ABSOLUTA . "admin/permisos_registrados");
        }elseif (empty($id_cancelar)) {
            create_flash_message('Hubo un problema con el valor inicial','error');
            redirect(RUTA_ABSOLUTA . "admin/permisos_registrados");
        }elseif(empty($cancelar_registro)){

            create_flash_message('Hubo un problema para cancelar el registro '. $cancelar_registro,'error');
            redirect(RUTA_ABSOLUTA . "admin/permisos_registrados");
        }elseif(!empty($user)){
            create_flash_message('Hubo un problema con los parámetros de usuario','error');
            redirect(RUTA_ABSOLUTA . "admin/permisos_registrados");
        }

        try {
            $select = "SELECT descripcion_registra,ruta_registra,id_registra FROM archivos WHERE id_permiso = :id_permiso";
            $statement = $pdo->prepare($select);
            $statement->bindParam(':id_permiso', $id_cancelar, PDO::PARAM_INT);
            $statement->execute();

            $resultado = $statement->fetch(PDO::FETCH_ASSOC);
            $descripcion_registra = $resultado['descripcion_registra'];
            $ruta_registra = $resultado['ruta_registra'];

            $ruta_registra = str_replace('/', DIRECTORY_SEPARATOR, $ruta_registra);

            if (file_exists($ruta_registra) && unlink($ruta_registra)) {
                $ruta_registra = null;
                $descripcion_registra = null;
            } else {
                $ruta_registra = $ruta_registra;
                $descripcion_registra = $descripcion_registra;
                throw new Exception("Hubo un error con el archivo");
            }

            // Iniciar la transacción
            $pdo->beginTransaction();

            $permiso = "UPDATE registros_permisos SET permiso_aceptado = :permiso_cancelado,usuario_registra = :user, ruta_registra=:ruta_registra WHERE id_permisos = :id_cancelar";
            //Premaramos la consulta
            $stmt = $pdo->prepare($permiso);

            //Parametros con sus valores
            $stmt->bindParam(':id_cancelar',$id_cancelar,PDO::PARAM_INT);
            $stmt->bindParam(':permiso_cancelado',$cancelar_registro,PDO::PARAM_STR);
            $stmt->bindParam(':user',$user,PDO::PARAM_STR);
            $stmt->bindParam(':ruta_registra',$ruta_registra,PDO::PARAM_STR);

            //Ejecutamos la consulta con los parametros
            $stmt->execute();

            $act = "UPDATE archivos SET descripcion_registra = :descripcion_registra ,ruta_registra=:ruta_registra,id_registra =:id_registra WHERE id_permiso = :id_permiso";

            $stmt = $pdo->prepare($act);

            $stmt->bindParam(':id_permiso',$id_cancelar,PDO::PARAM_INT);
            $stmt->bindParam(':id_registra',$id_registra,PDO::PARAM_INT);
            $stmt->bindParam(':descripcion_registra',$descripcion_registra,PDO::PARAM_STR);
            $stmt->bindParam(':ruta_registra',$ruta_registra,PDO::PARAM_STR);

            $stmt->execute();

            // Confirmar la transacción
            $pdo->commit();

            create_flash_message(
                'Se cancelo el registro del permiso',
                'success'
            );

            redirect(RUTA_ABSOLUTA . "admin/permisos_registrados");
        } catch (PDOException $e) {

            $pdo->rollBack();

            create_flash_message('Hubo un error en la base de datos', 'error');
            redirect(RUTA_ABSOLUTA . "solicitud_general");
        }catch (Exception $e) {
            create_flash_message($e->getMessage(), 'error');
            redirect(RUTA_ABSOLUTA . "admin/permisos_registrados");

        } finally {

            $pdo = null;
        }

    }
}


?>