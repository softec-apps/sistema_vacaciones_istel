<?php
include_once "../conexion.php";
include_once "../funciones_solicitudes.php";
include_once "../redirection.php";
include_once "../flash_messages.php";
function obtenerAnioMesActual() {
    $anio = date("Y");
    $mes = date("m");

    return array('anio' => $anio, 'mes' => $mes);
}
$anioMesArray = obtenerAnioMesActual();
$anio = $anioMesArray['anio'];
$mes = $anioMesArray['mes'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $directorioBase = $_SERVER['DOCUMENT_ROOT'] . "/sistema_vacaciones/htArchivos";

    $directorioAnio = $directorioBase . '/' . $anio;
    $directorioMes = $directorioAnio . '/' . $mes;

    $id = $_POST['id_permiso'];
    $archivoDescripcion = $_POST['archivoDescripcion'];

    // Obtener información del archivo
    $nombreOriginal = $_FILES['archivo']['name'];
    $nombreSinEspacios = str_replace(' ', '_', $nombreOriginal); // Reemplazar espacios por guiones bajos

    $nombreLimpio = quitarAcentos($nombreSinEspacios); // Quitar acentos y caracteres especiales
    $extension = pathinfo($nombreLimpio, PATHINFO_EXTENSION);

    // Crear un nombre único para el archivo
    $nombreUnico = uniqid() . '_' . $id . '.' . $extension;

    $route = $directorioMes . '/' . $nombreUnico;
    $file_tmp = $_FILES['archivo']['tmp_name'];

    try {

        if (!file_exists($directorioBase)) {
            mkdir($directorioBase, 0777, true);
        }

        if (!file_exists($directorioAnio)) {
            mkdir($directorioAnio, 0777, true);
        }

        if (!file_exists($directorioMes)) {
            mkdir($directorioMes, 0777, true);
        }
        // Verificar si la extensión es PDF
        if (strtolower($extension) != 'pdf') {
            throw new Exception("Solo se permiten archivos PDF.");
        } elseif (move_uploaded_file($file_tmp, $route)) {

            $route = str_replace("C:/xampp/htdocs/sistema_vacaciones/", '', $route);
            // Iniciar la transacción
            $pdo->beginTransaction();

            // Actualizar la tabla registros_permisos
            $consulta = "UPDATE registros_permisos SET ruta_solicita = :ruta_solicita  WHERE id_permisos = :id_permisos";
            $stmt = $pdo->prepare($consulta);
            $stmt->bindParam(':id_permisos', $id, PDO::PARAM_INT);
            $stmt->bindParam(':ruta_solicita', $route, PDO::PARAM_STR);
            $stmt->execute();

            // Insertar en la misma tabla y columna que el código anterior
            $insertArchivos = "INSERT INTO archivos (id_permiso,descripcion_solicita, ruta_solicita) VALUES (:id_permiso,:descripcion_solicita, :ruta_solicita)";
            $stmtArchivos = $pdo->prepare($insertArchivos);

            $stmtArchivos->bindParam(':id_permiso', $id, PDO::PARAM_INT);
            $stmtArchivos->bindParam(':descripcion_solicita', $archivoDescripcion, PDO::PARAM_STR);
            $stmtArchivos->bindParam(':ruta_solicita', $route, PDO::PARAM_STR);
            $stmtArchivos->execute();

            // Confirmar la transacción
            $pdo->commit();

            create_flash_message("Archivo guardado con éxito", 'success');
            redirect(RUTA_ABSOLUTA . "admin/permisos_pendientes");
        } else {

            create_flash_message("El archivo no se pudo guardar.", 'error');
            redirect(RUTA_ABSOLUTA . "admin/permisos_pendientes");
        }
    } catch (PDOException $e) {
        // Si hay un error, revierte la transacción
        $pdo->rollBack();

        echo $e->getMessage(), 'error';

        // redirect(RUTA_ABSOLUTA . "admin/permisos_pendientes");
    }catch (Exception $e) {
        // Manejar la excepción específica para archivos no PDF
        create_flash_message($e->getMessage(), 'error');
        redirect(RUTA_ABSOLUTA . "admin/permisos_pendientes");
    } finally {
        // Cerrar la conexión a la base de datos
        $pdo = null;
    }

}

// Función para quitar acentos y caracteres especiales
function quitarAcentos($cadena) {
    $cadena = strtr(utf8_decode($cadena), utf8_decode('áéíóúüñÁÉÍÓÚÜÑ'), 'aeiouunAEIOUUN');
    return utf8_encode($cadena);
}
?>
