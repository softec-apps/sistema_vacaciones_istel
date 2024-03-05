<?php
include_once "resta_solicitud.php";
include_once "funciones.php";
try {
    $resultadosTodosUsuarios = obtenerDiasTrabajadosParaTodos($pdo);

    foreach ($resultadosTodosUsuarios as $resultado) {
        $id_usuario = $resultado['id_usuario'];
        $cedulaIterada = $resultado['cedula'];
        $nombresIterado = $resultado['nombre'];
        $apellidosIterado = $resultado['apellido'];
        $diasTrabajados = $resultado['dias_trabajados'];
        $horasDePermisoSolicitadas = $resultado['horas_permiso'];
        $fechaIngreso = $resultado['fecha_ingreso'];
        $tiempoTrabajo = $resultado['tiempo_trabajo'];

        $diasDeVacaciones = calcularDiasVacaciones(
            $diasTrabajados,
            $horasDePermisoSolicitadas,
            $limiteVacaciones,
            $diasPorAnoTrabajado,
            $diasPorAno,
            $tiempoTrabajo
        );

        $diasDePermisoSolicitados = $horasDePermisoSolicitadas / $tiempoTrabajo;

        $dias_totales = $diasDeVacaciones + $diasDePermisoSolicitados;
        $porcentajeVerde = 50;
        $porcentajeAmarillo = 75;

        $limiteVerde = $limiteVacaciones*($porcentajeVerde/100);
        $limiteAmarillo = $limiteVacaciones*($porcentajeAmarillo/100);
        $limiteRojo = $limiteVacaciones;
        $horasTrabajadas = $diasTrabajados * $tiempoTrabajo;
        try {
            $prueba = calcular_actualizar($pdo,$diasTrabajados,$horasTrabajadas,$dias_totales,$diasDeVacaciones,$id_usuario);
            $mensaje = "Inicio de sesión correcto";
        } catch (PDOException $e) {
            $mensaje = "Hubo un problema";
        }
    }
    $mensaje = "Inicio de sesión correcto";
} catch (PDOException $e) {
    $mensaje = "Hubo un problema inténtelo mas tarde";
}

?>