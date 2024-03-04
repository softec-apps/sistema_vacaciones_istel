<?php
include_once "../conexion.php";
function total_usuarios($pdo){
    try {
        $con = "SELECT COUNT(*) as total FROM usuarios WHERE id_usuarios !=1";
        $stmt = $pdo->prepare($con);

        $stmt->execute();
        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Cerrar la conexión
        $pdo = null;
        return $res_vista;

    } catch (PDOExeception $e) {

        return "Error de exepcion" . $e->getMessage();
    }
}

function permisosRechazados($pdo){
    try {
        $con = "SELECT COUNT(*) as total FROM registros_permisos WHERE permiso_aceptado = 2";
        $stmt = $pdo->prepare($con);

        $stmt->execute();
        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Cerrar la conexión
        $pdo = null;
        return $res_vista;

    } catch (PDOExeception $e) {

        return "Error de exepcion" . $e->getMessage();
    }
}

function permisosRealizados($pdo){
    try {
        $con = "SELECT COUNT(*) as total FROM registros_permisos WHERE permiso_aceptado = 0";
        $stmt = $pdo->prepare($con);

        $stmt->execute();
        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Cerrar la conexión
        $pdo = null;
        return $res_vista;

    } catch (PDOExeception $e) {

        return "Error de exepcion" . $e->getMessage();
    }
}

function countAceptados($pdo){
    try {
        $con = "SELECT COUNT(*) as total FROM registros_permisos WHERE permiso_aceptado = 1";
        $stmt = $pdo->prepare($con);

        $stmt->execute();
        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Cerrar la conexión
        $pdo = null;
        return $res_vista;

    } catch (PDOExeception $e) {

        return "Error de exepcion" . $e->getMessage();
    }
}

function countRegistrados($pdo){
    try {
        $con = "SELECT COUNT(*) as total FROM registros_permisos WHERE permiso_aceptado = 3";
        $stmt = $pdo->prepare($con);

        $stmt->execute();
        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Cerrar la conexión
        $pdo = null;
        return $res_vista;

    } catch (PDOExeception $e) {

        return "Error de exepcion" . $e->getMessage();
    }
}
?>