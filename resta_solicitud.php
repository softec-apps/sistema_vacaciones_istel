<?php
include_once "../conexion.php";

function seleccionar($pdo){
    try {
        $consulta = "SELECT limiteVacaciones, diasPorAño, diasAnuales FROM configuracion";
        $stmt = $pdo->prepare($consulta);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Asignar los valores a las variables
        $limiteVacaciones = $resultado["limiteVacaciones"];
        $diasPorAnoTrabajado = $resultado["diasPorAño"];
        $diasPorAno = $resultado["diasAnuales"];

        // Puedes devolver las variables si es necesario
        return [
            'limiteVacaciones' => $limiteVacaciones,
            'diasPorAnoTrabajado' => $diasPorAnoTrabajado,
            'diasPorAno' => $diasPorAno
        ];
    } catch (PDOException $e) {
        echo "Error de exepcion en la funcion de limite" .$e->getMessage();
    }

}

$seleccionar = seleccionar($pdo);
// Puedes acceder a las variables individualmente
$limiteVacaciones = $seleccionar['limiteVacaciones'];
$diasPorAnoTrabajado = $seleccionar['diasPorAnoTrabajado'];
$diasPorAno = $seleccionar['diasPorAno'];


function calcularDiasVacaciones($diasTrabajados, $horasDePermiso, $limiteVacaciones, $diasPorAnoTrabajado, $diasPorAno, $horasDiariasTrabajo) {
    $anosTrabajados = obtenerAnosTrabajados($diasTrabajados, $diasPorAno);
    $diasVacaciones = $anosTrabajados * $diasPorAnoTrabajado;

    // Descuenta las horas de permiso del total de días de vacaciones
    $diasVacaciones -= $horasDePermiso / $horasDiariasTrabajo; // 8 o 4 horas = 1 día

    // Limita los días de vacaciones al límite establecido
    // $diasVacaciones = min($diasVacaciones, $limiteVacaciones);

    return $diasVacaciones;
}
function obtenerAnosTrabajados($diasTrabajados, $diasPorAno) {
    $anosTrabajados = floor($diasTrabajados / $diasPorAno);
    return $anosTrabajados;
}


function obtenerDiasTrabajadosParaUsuario($pdo, $id_usuario) {
    try {
        $query = "SELECT id_usuarios, cedula, nombres, apellidos, tiempo_trabajo FROM usuarios WHERE id_usuarios = :id";
        $statement = $pdo->prepare($query);
        $statement->bindParam(':id', $id_usuario, PDO::PARAM_INT);
        $statement->execute();

        $usuario = $statement->fetch(PDO::FETCH_ASSOC);

        $resultados = [];

        if ($usuario) {
            $id_usuario = $usuario['id_usuarios'];
            $diasTrabajados = consulta_unica($pdo, $id_usuario);
            $horasDePermiso = horas_ocupadas($pdo, $id_usuario);
            $fechaIngreso = obtenerFechaIngreso($pdo, $id_usuario);

            $resultados[] = [
                'id_usuario' => $id_usuario,
                'cedula' => $usuario['cedula'],
                'nombre' => $usuario['nombres'],
                'apellido' => $usuario['apellidos'],
                'tiempo_trabajo' => $usuario['tiempo_trabajo'],
                'dias_trabajados' => $diasTrabajados,
                'horas_permiso' => $horasDePermiso,
                'fecha_ingreso' => $fechaIngreso,
            ];
        }

        return $resultados;
    } catch (PDOException $e) {
        echo "Error de excepción en la función de Días trabajados para un solo usuario: " . $e->getMessage();
    }
}



function obtenerDiasTrabajadosParaTodos($pdo) {
    try {
        $query = "SELECT id_usuarios,cedula,nombres,apellidos,tiempo_trabajo FROM usuarios WHERE rol = 'Funcionario'"; // Obtén todos los usuarios Funcionarios
        $statement = $pdo->prepare($query);
        $statement->execute();

        $usuarios = $statement->fetchAll(PDO::FETCH_ASSOC);

        $resultados = [];

        foreach ($usuarios as $usuario) {
            $id_usuario = $usuario['id_usuarios'];
            $diasTrabajados = consulta_unica($pdo, $id_usuario);
            $horasDePermiso = horas_ocupadas($pdo, $id_usuario);
            $fechaIngreso = obtenerFechaIngreso($pdo, $id_usuario); // Agregar función para obtener la fecha de ingreso
            $cedulaIterada = $usuario['cedula'];
            $nombresIterado = $usuario['nombres'];
            $apellidosIterado = $usuario['apellidos'];
            $tiempo_trabajo = $usuario['tiempo_trabajo'];
            $resultados[] = [
                'id_usuario' => $id_usuario,
                'cedula' => $cedulaIterada,
                'nombre' => $nombresIterado,
                'apellido' => $apellidosIterado,
                'tiempo_trabajo' => $tiempo_trabajo,
                'dias_trabajados' => $diasTrabajados,
                'horas_permiso' => $horasDePermiso,
                'fecha_ingreso' => $fechaIngreso,
            ];
        }

        return $resultados;
    } catch (PDOException $e) {
        echo "Error de exepcion en la funcion de Dias trabajados" .$e->getMessage();
    }
}

function obtenerUsuariosConPermios($pdo) {
    try {
        $query = "SELECT usuarios.id_usuarios,registros_permisos.id_permisos,usuarios.cedula,usuarios.nombres,usuarios.apellidos,usuarios.tiempo_trabajo,registros_permisos.permiso_aceptado,registros_permisos.ruta_solicita,registros_permisos.ruta_aprueba,registros_permisos.ruta_registra FROM usuarios,registros_permisos WHERE usuarios.id_usuarios = registros_permisos.id_usuarios AND rol = 'Funcionario'"; // Obtén todos los usuarios Funcionarios
        $statement = $pdo->prepare($query);
        $statement->execute();

        $usuarios = $statement->fetchAll(PDO::FETCH_ASSOC);

        $resultados = [];

        foreach ($usuarios as $usuario) {
            $id_usuario = $usuario['id_usuarios'];
            $diasTrabajados = consulta_unica($pdo, $id_usuario);
            $horasDePermiso = horas_ocupadas($pdo, $id_usuario);
            $fechaIngreso = obtenerFechaIngreso($pdo, $id_usuario); // Agregar función para obtener la fecha de ingreso
            $id_permisos = $usuario['id_permisos'];
            $cedulaIterada = $usuario['cedula'];
            $nombresIterado = $usuario['nombres'];
            $apellidosIterado = $usuario['apellidos'];
            $tiempo_trabajo = $usuario['tiempo_trabajo'];
            $permisoUsuario = $usuario['permiso_aceptado'];
            $ruta_solicita = $usuario['ruta_solicita'];
            $ruta_aprueba = $usuario['ruta_aprueba'];
            $ruta_registra = $usuario['ruta_registra'];
            $resultados[] = [
                'id_usuario' => $id_usuario,
                'id_permisos' => $id_permisos,
                'cedula' => $cedulaIterada,
                'nombre' => $nombresIterado,
                'apellido' => $apellidosIterado,
                'tiempo_trabajo' => $tiempo_trabajo,
                'dias_trabajados' => $diasTrabajados,
                'horas_permiso' => $horasDePermiso,
                'fecha_ingreso' => $fechaIngreso,
                'permisoUsuario' => $permisoUsuario,
                'ruta_solicita' => $ruta_solicita,
                'ruta_aprueba' => $ruta_aprueba,
                'ruta_registra' => $ruta_registra,
            ];
        }

        return $resultados;
    } catch (PDOException $e) {
        echo "Error de exepcion en la funcion de Dias trabajados" .$e->getMessage();
    }
}

function obtenerFechaIngreso($pdo, $id_usuario) {
    try {
        $query = "SELECT fecha_ingreso FROM usuarios WHERE id_usuarios = :id";
        $statement = $pdo->prepare($query);
        $statement->bindParam(':id', $id_usuario, PDO::PARAM_INT);
        $statement->execute();

        $resultado = $statement->fetch(PDO::FETCH_ASSOC);
        return $resultado['fecha_ingreso'];

    } catch (PDOException $e) {
        echo "Error de exepcion en la funcion de Fecha Ingreso" .$e->getMessage();
    }

}

function consulta_unica($pdo, $id_usuario_insertado) {
    try {
        // Obtener la fecha de ingreso del usuario
        $query = "SELECT fecha_ingreso FROM usuarios WHERE id_usuarios = :id";
        $statement = $pdo->prepare($query);
        $statement->bindParam(':id', $id_usuario_insertado, PDO::PARAM_INT);
        $statement->execute();

        $resultado = $statement->fetch(PDO::FETCH_ASSOC);
        $fecha_ingreso_usuario = $resultado['fecha_ingreso'];

        // Crear objetos DateTime para las fechas
        $fecha_actual_obj = new DateTime(date('Y-m-d'));

        $fecha_ingreso_obj = new DateTime($fecha_ingreso_usuario);

        // Calcular los días y las horas trabajadas
        $intervalo = $fecha_ingreso_obj->diff($fecha_actual_obj);
        $diasTrabajados = $intervalo->days;

        return $diasTrabajados;

    } catch (PDOException $e) {
        echo "Error de exepcion en la funcion de consulta" .$e->getMessage();
    }

}

function horas_ocupadas($pdo, $id_usuario_insertado) {
    try {
        $consulta_select = "SELECT SUM(horas_ocupadas) AS total_horas_ocupadas FROM registros_permisos WHERE id_usuarios = :id_trb AND (permiso_aceptado = 1 OR permiso_aceptado = 3)";

        $stmt = $pdo->prepare($consulta_select);
        $stmt->bindParam(':id_trb', $id_usuario_insertado, PDO::PARAM_STR);
        $stmt->execute();

        $result_select = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result_select && isset($result_select['total_horas_ocupadas'])) {
            return $result_select['total_horas_ocupadas'];
        } else {
            // Manejar la situación en la que no se obtuvieron resultados o no hay 'total_horas_ocupadas'
            return 0; // O cualquier valor predeterminado que desees usar
        }

    } catch (PDOException $e) {
        echo "Error de exepcion en la funcion de consulta" .$e->getMessage();
    }
}


