<?php
include_once "conexion.php";

function diasSelect($id,$pdo){
    try {
        $consulta = "SELECT usuarios.id_usuarios,usuarios.cedula,usuarios.nombres,usuarios.apellidos, registros_permisos.horas_ocupadas,registros_permisos.permiso_aceptado,registros_permisos.dias_solicitados,registros_permisos.horas_solicitadas,usuarios.tiempo_trabajo FROM usuarios,registros_permisos WHERE usuarios.id_usuarios = registros_permisos.id_usuarios AND registros_permisos.id_usuarios = :id_usuario";
        $stmt = $pdo->prepare($consulta);

        $stmt->bindParam(':id_usuario',$id,PDO::PARAM_STR);
        $stmt->execute();
        $res_vista_permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res_vista_permisos;

    } catch (PDOException $e) {
        return "Error de exepcion" . $e->getMessage();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'])) {
    $id_usuario = $_POST['id_usuario'];

    // Llamada a la función que obtiene los nuevos datos
    $nuevosDatos = diasSelect($id_usuario, $pdo);

    // Generar el HTML para la tabla con los nuevos datos
    $htmlTabla = '';
    foreach ($nuevosDatos as $valor) {
        $htmlTabla .= '<tr>';
        $htmlTabla .= '<td>' . $valor['cedula'] . '</td>';
        $htmlTabla .= '<td>' . $valor['nombres'] . '</td>';
        $htmlTabla .= '<td>' . $valor['apellidos'] . '</td>';
        $horasOcupadasMultiplicadas = $valor['horas_ocupadas'];
        $permiso_aceptado = $valor['permiso_aceptado'];
        $dias_solicitados = $valor['dias_solicitados'];
        $horas_solicitadas = $valor['horas_solicitadas'];
        $horas_formateadas = date('H', strtotime($horas_solicitadas));
        $tiempo_trabajo = $valor['tiempo_trabajo'];

        // Verificar si las horas ocupadas son iguales o superiores a 8
        if ($valor['horas_ocupadas'] >= 8) {
            // Multiplicar las horas ocupadas por 8
            $horasOcupadasMultiplicadas = $horasOcupadasMultiplicadas / $tiempo_trabajo;

            $htmlTabla .= '<td>' . $horasOcupadasMultiplicadas . '</td>';
            $htmlTabla .= '<td> 0 </td>';
            $tipo_solicitud = "SI";
        }elseif ($valor['horas_ocupadas'] == 0) {
            $htmlTabla .= '<td>' . $dias_solicitados . '</td>';
            $htmlTabla .= '<td>' . $horas_formateadas . '</td>';
            $tipo_solicitud = "NO";
        } else {
            // Mostrar las horas ocupadas y una columna vacía
            $htmlTabla .= '<td> 0 </td>';
            $htmlTabla .= '<td>' . $horasOcupadasMultiplicadas . '</td>';
            $tipo_solicitud = "SI";
        }

        $htmlTabla .= '<td>' . $tipo_solicitud . '</td>';

        if ($permiso_aceptado == 2):
            $htmlTabla .= '<td><button class="btn btn-danger" title="Permiso Rechazado" ><i class="fa-solid fa-xmark"></i></button></td>';

        elseif ($permiso_aceptado == 1 || $permiso_aceptado == 3):
            $htmlTabla .= '<td><button class="btn btn-success" title="Aceptado" ><i class="fa-solid fa-check"></i></button></td>';

        elseif ($permiso_aceptado == 0):
            $htmlTabla .= '<td><button class="btn btn-primary" title="Solicitud en proceso"><i class="fa-solid fa-sync fa-spin"></i></button></td>';

        endif;

        $htmlTabla .= '</tr>';
    }

    echo $htmlTabla;
} else {
    // Manejar caso en que no se proporciona el id_usuario
    echo 'Error: Falta el parámetro id_usuario.';
}

?>
