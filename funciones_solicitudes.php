<?php


function validarExistenciaRegistro($pdo, $id_usuarios, $fecha_permiso_desde_obj, $fecha_permiso_hasta_obj) {
    try {
        $consulta_existencia = "SELECT COUNT(*) AS count FROM registros_permisos WHERE id_usuarios = :id_usuarios AND fecha_permisos_desde = :fecha_permisos_desde AND fecha_permiso_hasta = :fecha_permiso_hasta";
        $stmt_existencia = $pdo->prepare($consulta_existencia);
        $stmt_existencia->bindParam(':id_usuarios', $id_usuarios, PDO::PARAM_INT);
        $stmt_existencia->bindParam(':fecha_permisos_desde', $fecha_permiso_desde_obj->format('Y-m-d'), PDO::PARAM_STR);
        $stmt_existencia->bindParam(':fecha_permiso_hasta', $fecha_permiso_hasta_obj->format('Y-m-d'), PDO::PARAM_STR);
        $stmt_existencia->execute();
        $resultado_existencia = $stmt_existencia->fetch(PDO::FETCH_ASSOC);

        return ($resultado_existencia['count'] > 0) ? true : false;
    } catch (PDOException $e) {
        return false;
    }
}


function validarExistenciaHoras($pdo, $id_usuarios, $horas_permiso_desde, $horas_permiso_hasta, $fecha_permiso) {
    try {
        $consultaHoras = "SELECT COUNT(*) AS contar FROM registros_permisos WHERE id_usuarios = :id_usuarios AND horas_permiso_desde = :horas_permiso_desde AND horas_permiso_hasta = :horas_permiso_hasta AND fecha_permiso = :fecha_permiso AND horas_permiso_desde != '00:00:00' AND horas_permiso_hasta != '00:00:00' ";
        $stmtHoras = $pdo->prepare($consultaHoras);
        $stmtHoras->bindParam(':id_usuarios', $id_usuarios, PDO::PARAM_INT);
        $stmtHoras->bindParam(':horas_permiso_desde', $horas_permiso_desde, PDO::PARAM_STR);
        $stmtHoras->bindParam(':horas_permiso_hasta', $horas_permiso_hasta, PDO::PARAM_STR);
        $stmtHoras->bindParam(':fecha_permiso', $fecha_permiso, PDO::PARAM_STR);
        $stmtHoras->execute();
        $resultadoHoras = $stmtHoras->fetch(PDO::FETCH_ASSOC);

        return ($resultadoHoras['contar'] > 0) ? true : false;
    } catch (PDOException $e) {
        return false;
    }
}

function obtenerTiempoTrabajo($pdo, $id_usuarios) {
    try {
        $consulta = "SELECT tiempo_trabajo FROM usuarios WHERE id_usuarios = :id";
        $statement = $pdo->prepare($consulta);
        $statement->bindParam(':id', $id_usuarios, PDO::PARAM_INT);
        $statement->execute();

        $resultado = $statement->fetch(PDO::FETCH_ASSOC);
        return $resultado['tiempo_trabajo'];
    } catch (PDOException $e) {
        return "Error"  . $e->get_message();
    }
}

function actualizarArchivos($pdo,$id_permisos,$ruta_solicita){
    try {
        $consulta = "UPDATE registros_permisos SET ruta_solicita = :ruta_solicita  WHERE id_permisos = :id_permisos";
        $stmt = $pdo->prepare($consulta);
        $stmt->bindParam(':id_permisos',$id_permisos,PDO::PARAM_INT);
        $stmt->bindParam(':ruta_solicita',$ruta_solicita,PDO::PARAM_STR);

        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function actualizarArchivosJefe($pdo,$id_permisos,$ruta_aprueba){
    try {
        $consulta = "UPDATE registros_permisos SET ruta_aprueba = :ruta_aprueba  WHERE id_permisos = :id_permisos";
        $stmt = $pdo->prepare($consulta);
        $stmt->bindParam(':id_permisos',$id_permisos,PDO::PARAM_INT);
        $stmt->bindParam(':ruta_aprueba',$ruta_aprueba,PDO::PARAM_STR);

        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        return false;
    }
}
?>