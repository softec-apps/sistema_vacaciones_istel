<?php
include_once "../conexion.php";
include_once "../funciones.php";
include_once "../flash_messages.php";
$anioMesArray = obtenerAnioMesActual();
$anio = $anioMesArray['anio'];
$mes = $anioMesArray['mes'];
if ($_POST) {

    // Verificar si el formulario de registro fue enviado
    if(isset($_POST["registrar"])) {
        $id_registrar = $_POST["id_registrar"];
        $id_user = $_POST["id_user"];
        $registrar = $_POST["registrar"];
        $user = $_POST["user"];
        $archivoDescripcion = $_POST["archivoDescripcion"];
        $archivoRegistra = $_FILES["archivoRegistra"];
        if ($registrar!=3) {
            create_flash_message('Ocurrió un error con el valor de la solicitud','error');
            redirect(RUTA_ABSOLUTA . "talentoHumano/permisosAprobados");
        }

        // Obtener la ruta del script actual
        $baseDir = __DIR__;

        // Construir rutas relativas basadas en $baseDir
        $directorioBase = realpath($baseDir . '/..') . "/htArchivos";

        $directorioAnio = $directorioBase . '/' . $anio;
        $directorioMes = $directorioAnio . '/' . $mes;

        $nombreOriginal = $archivoRegistra['name'];
        $file_tmp = $archivoRegistra['tmp_name'];

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
                echo "Solo se permiten archivos PDF.";
            } elseif (move_uploaded_file($file_tmp, $route)) {

                $permiso = "UPDATE registros_permisos SET permiso_aceptado = :permiso_aceptado,usuario_registra = :user,ruta_registra = :ruta_registra WHERE id_permisos = :id_permisos";
                //Preparamos la consulta
                $stmt = $pdo->prepare($permiso);

                //Parametros con sus valores
                $stmt->bindParam(':id_permisos',$id_registrar,PDO::PARAM_INT);
                $stmt->bindParam(':permiso_aceptado',$registrar,PDO::PARAM_STR);
                $stmt->bindParam(':user',$user,PDO::PARAM_STR);
                $stmt->bindParam(':ruta_registra',$route,PDO::PARAM_STR);

                //Ejecutamos la consulta con los parametros
                $stmt->execute();

                $insertArchivos = "UPDATE archivos SET descripcion_registra = :descripcion_registra,ruta_registra = :ruta_registra, id_registra =:id_user WHERE id_permiso = :id_permiso";
                $stmtArchivos = $pdo->prepare($insertArchivos);

                $stmtArchivos->bindParam(':id_permiso', $id_registrar, PDO::PARAM_INT);
                $stmtArchivos->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                $stmtArchivos->bindParam(':descripcion_registra', $archivoDescripcion, PDO::PARAM_STR);
                $stmtArchivos->bindParam(':ruta_registra', $route, PDO::PARAM_STR);

                $stmtArchivos->execute();

                // Confirmar la transacción
                $pdo->commit();

                create_flash_message('Permiso registrado correctamente','success');
                redirect(RUTA_ABSOLUTA . "talentoHumano/permisosAprobados");
            } else {
                throw new Exception("Error: El archivo no se pudo guardar.");
            }


        } catch (PDOException $e) {
            $pdo->rollBack();
            create_flash_message('Ocurrió un error en la base de datos', 'error');
            redirect(RUTA_ABSOLUTA . "talentoHumano/permisosAprobados");
        } catch (Exception $e) {
            $pdo->rollBack();
            create_flash_message($e->getMessage(), 'error');
            redirect(RUTA_ABSOLUTA . "talentoHumano/permisosAprobados");
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
            redirect(RUTA_ABSOLUTA . "talentoHumano/permisosRegistrados");
        }elseif (empty($id_cancelar)) {
            create_flash_message('Hubo un problema con el valor inicial','error');
            redirect(RUTA_ABSOLUTA . "talentoHumano/permisosRegistrados");
        }elseif(empty($cancelar_registro)){

            create_flash_message('Hubo un problema para cancelar el registro '. $cancelar_registro,'error');
            redirect(RUTA_ABSOLUTA . "talentoHumano/permisosRegistrados");
        }elseif(!empty($user)){
            create_flash_message('Hubo un problema con los parámetros de usuario','error');
            redirect(RUTA_ABSOLUTA . "talentoHumano/permisosRegistrados");
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

            redirect(RUTA_ABSOLUTA . "talentoHumano/permisosRegistrados");

        }catch (PDOException $e) {

            $pdo->rollBack();

            create_flash_message('Hubo un error en la base de datos', 'error');
            redirect(RUTA_ABSOLUTA . "talentoHumano/permisosRegistrados");
        }catch (Exception $e) {
            create_flash_message($e->getMessage(), 'error');
            redirect(RUTA_ABSOLUTA . "talentoHumano/permisosRegistrados");

        } finally {

            $pdo = null;
        }
    }
}


?>