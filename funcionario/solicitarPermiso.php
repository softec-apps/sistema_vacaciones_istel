<?php
include_once "../conexion.php";
include_once "../funciones.php";
include_once "../flash_messages.php";

// $seleccionar = seleccionarConfi($pdo);
// $numero = $seleccionar['numero'];
// $numero_formateado = number_format($numero, 14);

if ($_POST) {
    $id_usuarios=$_POST["id_usuario"];
    $nombre_usuario =$_POST["nombre_usuario"];
    $regimen =$_POST["regimen"];
    $fecha_permisos_desde =$_POST["fecha_inicio"];
    $fecha_permiso_hasta = $_POST["fecha_fin"];
    $horas_permiso_desde =$_POST["hora_inicio"];
    $horas_permiso_hasta = $_POST["hora_fin"];
    $motivo_permiso =$_POST["motivo"];
    $permiso_aceptado = $_POST["permiso_aceptado"];
    $observaciones =$_POST["observaciones"];


    $provincia = "BOLIVAR";
    $coordinacion_zonal = "COORDINACION ZONAL 5 Y 8";
    $direccion_unidad = "INSTITUTO SUPERIOR TECNOLÓGICO EL LIBERTADOR ";
    //no calcuar
    $fecha_permiso =  date('Y-m-d');
    // Convertir las fechas y horas a objetos DateTime
    $fecha_permiso_desde_obj = new DateTime($fecha_permisos_desde . ' ' . $horas_permiso_desde);
    $fecha_permiso_hasta_obj = new DateTime($fecha_permiso_hasta . ' ' . $horas_permiso_hasta);

    // Calcular la diferencia completa
    $intervalo_completo = $fecha_permiso_desde_obj->diff($fecha_permiso_hasta_obj);

    // Obtener días y horas
    $dias_solicitados = $intervalo_completo->days;
    $horas_solicitadas = $intervalo_completo->h;

    // Obtener días y horas
    include_once "../funciones_solicitudes.php";
    // Llamamos a las funciones de validación
    $existeRegistro = validarExistenciaRegistro($pdo, $id_usuarios, $fecha_permiso_desde_obj, $fecha_permiso_hasta_obj);
    $existeHoras = validarExistenciaHoras($pdo, $id_usuarios, $horas_permiso_desde, $horas_permiso_hasta, $fecha_permiso);

    if ($existeRegistro) {
        create_flash_message(
            'Ya existe un permiso con las mismas fechas de inicio y de fin',
            'error'
        );
        redirect(RUTA_ABSOLUTA . "funcionario/solicitudUser");
        exit();
    } elseif ($existeHoras) {
        create_flash_message(
            'Ya existe un permiso con las mismas horas de inicio y de fin para este dia',
            'error'
        );
        redirect(RUTA_ABSOLUTA . "funcionario/solicitudUser");
        exit();
    }
    $tiempo_trabajo = obtenerTiempoTrabajo($pdo, $id_usuarios);

    if ($tiempo_trabajo === false) {
        create_flash_message(
            'Hubo un error al obtener el tiempo de trabajo',
            'error'
        );

        redirect(RUTA_ABSOLUTA . "funcionario/solicitudUser");
        exit();
    }


    $dias_solicitados2 = $dias_solicitados * 24;
    if (empty($dias_solicitados)) {
        $dias_solicitados;
    }else {
        $dias_solicitados -=1;
    }

    // Si hay minutos, agregarlos
    if ($intervalo_completo->i > 0) {
        // Convertir minutos a formato HH:mm
        $horas_solicitadas += ($intervalo_completo->i / 60);
    }

    // Formatear las horas en formato HH:mm
    $horas_solicitadas2 = sprintf("%02d:%02d", floor($horas_solicitadas), ($horas_solicitadas - floor($horas_solicitadas)) * 60);
    if ($horas_solicitadas !== 0) {
        $valor_mostrar = $horas_solicitadas2;
        $horas_ocupadas = $valor_mostrar;
    } elseif ($dias_solicitados !== 0) {
        $numeroCambio = 1.36363636363636;
        $valor_mostrar = $dias_solicitados;
        $xMultiplicar = $valor_mostrar * $numeroCambio;
        $horas_ocupadas = substr((string)$xMultiplicar, 0, 4);
        $dias_totales = $horas_ocupadas;
        $horas_ocupadas = $horas_ocupadas * $tiempo_trabajo;
    }
    $desc_motivo = "";
    $tiempoLimite_motivo ="";
    switch ($motivo_permiso) {
        case 'LICENCIA_POR_CALAMIDAD_DOMESTICA':
            $tiempoLimite_motivo ="HASTA 8 DÍAS; SEGÚN EL CASO";
            $limite_horas = 192;
            $desc_motivo = "Por calamidad doméstica, entendida como tal, al fallecimiento, accidente o enfermedad grave del cónyuge o conviviente en unión de hecho legalmente reconocida o de los parientes hasta el segundo grado de consanguinidad o segundo de afinidad de las servidoras o servidores públicos. Para el caso del cónyuge o conviviente en unión de hecho legalmente reconocida, del padre, madre o hijos, la máxima autoridad, su delegado o las Unidades de Administración del Talento Humano deberán conceder licencia hasta por ocho días, al igual que para el caso de siniestros que afecten gravemente la propiedad o los bienes de la servidora o servidor. Para el resto de parientes contemplados en este literal, se concederá la licencia hasta por tres días y, en caso de requerir tiempo adicional, se lo contabilizará con cargo a vacaciones;";
            $horas_ocupadas = 0;
            break;

        case 'LICENCIA_POR_ENFERMEDAD':
            $tiempoLimite_motivo ="PUEDE SER DE HASTA TRES MESES - SEIS MESES SEGÚN SEA EL CASO";
            $limite_horas = 4383;
            $desc_motivo = "Por enfermedad que determine imposibilidad física o psicológica, debidamente comprobada, para la realización de sus labores, hasta por tres meses; e, igual período podrá aplicarse para su rehabilitación; Por enfermedad catastrófica o accidente grave debidamente certificado, hasta por seis meses; así como el uso de dos horas diarias para su rehabilitación en caso de prescripción médica;";
            $horas_ocupadas = 0;
            break;

        case 'LICENCIA_POR_MATERNIDAD':
            $tiempoLimite_motivo ="84 DIAS";
            $limite_horas = 2016;
            $desc_motivo = "Por maternidad, toda servidora pública tiene derecho a una licencia con remuneración de doce (12) semanas por el nacimiento de su hija o hijo; en caso de nacimiento múltiple el plazo se extenderá por diez días adicionales. La ausencia se justificará mediante la presentación del certificado médico otorgado por un facultativo del Instituto Ecuatoriano de Seguridad Social; y, a falta de éste, por otro profesional de los centros de salud pública. En dicho certificado se hará constar la fecha probable del parto o en la que tal hecho se produjo;";
            $horas_ocupadas = 0;
            // $tiempo_final = 84;
            break;
        case 'LICENCIA_POR_MATRIMONIO_O_UNION_DE_ECHO':
            $tiempoLimite_motivo ="3 DÍAS LABORABLES (HÁBILES)";
            $limite_horas = 72;
            $desc_motivo = "La servidora o el servidor que contraiga matrimonio o unión de hecho, tendrá derecho a una licencia con remuneración de tres días hábiles continuos en total, pudiendo solicitarla antes o después de la celebración del matrimonio.";
            $horas_ocupadas = 0;
            break;

        case 'LICENCIA_POR_PATERNIDAD':
            $tiempoLimite_motivo ="10 DÍAS (NORMAL), 15 DÍAS (CESAREA) Y 8 DÍAS MÁS PREMATURO, y 25 días Enfermedades Degenerativas etc.";
            $desc_motivo = "Por paternidad, el servidor público tiene derecho a licencia con remuneración por el plazo de diez días contados desde el nacimiento de su hija o hijo cuando el parto es normal; en los casos de nacimiento múltiple o por cesárea se ampliará por cinco días más; En los casos de nacimientos prematuros o en condiciones de cuidado especial, se prolongará la licencia por paternidad con remuneración por ocho días más; y, cuando hayan nacido con una enfermedad degenerativa, terminal o irreversible o con un grado de discapacidad severa, el padre podrá tener licencia con remuneración por veinte y cinco días, hecho que se justificará con la presentación de un certificado médico, otorgado por un facultativo del Instituto Ecuatoriano de Seguridad Social y a falta de éste, por otro profesional médico debidamente avalado por los centros de salud pública; En caso de fallecimiento de la madre, durante el parto o mientras goza de la licencia por maternidad, el padre podrá hacer uso de la totalidad, o en su caso de la parte que reste del período de licencia que le hubiere correspondido a la madre;";
            // $tiempo_final = 10 || 15 || 18 || 25;
            $limite_horas = 600;
            $horas_ocupadas = 0;
            break;

        case 'PERMISO_PARA_ESTUDIOS_REGULARES':
            $tiempoLimite_motivo ="HASTA DOS HORAS RECUPERABLES";
            $desc_motivo = "En el caso de contratos de servicios ocasionales se podrá otorgar este permiso de conformidad con las necesidades institucionales siempre que la servidora o el servidor recupere el tiempo solicitado.";
            $limite_horas = 2;
            $horas_ocupadas = 0;
            break;

        case 'PERMISO_DE_DIAS_CON_CARGO_A_VACACIONES':
            $tiempoLimite_motivo ="Tomar en cuenta que los días de vacaciones son en un numero de 30 días (22 laborales, 8 sábados y domingos), después de 11 meses laborados, el cálculo se lo realiza multiplicando el tiempo laboral solicitado por 1,36363636363636";
            $desc_motivo = "Podrán concederse permisos imputables a vacaciones, siempre que éstos no excedan los días de vacación a los que la servidora o el servidor tenga derecho al momento de la solicitud.  (se suman horas, fracciones de horas y días)";
            $limite_horas = null;
            break;

        case 'PERMISO_POR_ASUNTOS_OFICIALES':
            $tiempoLimite_motivo ="NO IMPUTABLE A VACACIONES";
            $desc_motivo = "Esta licencia se hará efectiva siempre y cuando exista la disposición por su jefe inmediato de realizar labores innherentes a su puesto o a la Institución, o a su vez sea dispuesto por la máxima autoridad.";
            $limite_horas = null;
            $horas_ocupadas = 0;
            break;
        case 'PERMISO_PARA_ATENCION_MEDICA':
            $tiempoLimite_motivo ="HASTA POR 2 HORAS";
            $desc_motivo = "Las y los servidores/as tendrán derecho a permiso para atención médica hasta por dos horas, siempre que se justifique con certificado médico correspondiente otorgado por el Instituto Ecuatoriano de Seguridad Social o abalizado por los centros de salud pública.";
            $limite_horas = 2;
            $horas_ocupadas = 0;
            break;

        case 'OTROS':
            $tiempoLimite_motivo ="SEGÚN EL DETALLE SE LE ASIGNARÁ LA CATEGORÍA Y EL PERIODO";
            $desc_motivo = "Los demás que contempla la ley Orgánica del Servicio Público, su Reglamento, y el Reglamento Interno de la Institución.";
            $limite_horas = null;
            // $horas_ocupadas = 0;
            break;

        default:
            $tiempoLimite_motivo = "Vacio";
            $desc_motivo = "Vacio";
            break;
    }
    // Verificar y mostrar mensaje de error si excede el límite
    if ($limite_horas !== null && $horas_solicitadas > $limite_horas) {

        create_flash_message(
            'Las horas solicitadas exceden el límite permitido para este caso (' . $limite_horas .'horas).',
            'error'
        );

        redirect(RUTA_ABSOLUTA . "funcionario/solicitudUser");
        exit();
    }elseif ($limite_horas !== null && $dias_solicitados2 > $limite_horas) {
        create_flash_message(
            'Los días solicitadas exceden el límite permitido para este caso ' . ($limite_horas/24) . 'dias.',
            'error'
        );

        redirect(RUTA_ABSOLUTA . "funcionario/solicitudUser");
        exit();
    }

    $usuario_aprueba  = "";
    $usuario_registra   = "";

    try {
        $pdo->beginTransaction();

        // Obtener los días disponibles de vacaciones del usuario
        $consulta_dias_vacaciones = "SELECT dias_totales_vac_user FROM dias_trabajo WHERE id_usuarios = :id_usuario";
        $stmt_dias_vacaciones = $pdo->prepare($consulta_dias_vacaciones);
        $stmt_dias_vacaciones->bindParam(':id_usuario', $id_usuarios, PDO::PARAM_INT);
        $stmt_dias_vacaciones->execute();
        $dias_disponibles = $stmt_dias_vacaciones->fetchColumn();

        if ($motivo_permiso == "PERMISO_DE_DIAS_CON_CARGO_A_VACACIONES" && $dias_disponibles == 0) {
            create_flash_message(
                'Las horas o dias solicitados no pueden ser generados si el usuaro no tiene dias disponibles de vacaciones',
                'error'
            );
            redirect(RUTA_ABSOLUTA . "funcionario/solicitudUser");
        }
        else{

            $consult_permisos = "INSERT INTO registros_permisos(id_usuarios,provincia,regimen,coordinacion_zonal,direccion_unidad,fecha_permiso,observaciones,motivo_permiso,tiempo_motivo,desc_motivo,dias_solicitados,dias_totales,horas_solicitadas,fecha_permisos_desde,fecha_permiso_hasta,horas_permiso_desde,horas_permiso_hasta,usuario_solicita,usuario_aprueba,usuario_registra,permiso_aceptado,horas_ocupadas)VALUES(:id_usuarios,:provincia,:regimen,:coordinacion_zonal,:direccion_unidad,:fecha_permiso,:observaciones,:motivo_permiso,:tiempo_motivo,:desc_motivo,:dias_solicitados,:dias_totales,:horas_solicitadas,:fecha_permisos_desde,:fecha_permiso_hasta,:horas_permiso_desde,:horas_permiso_hasta,:usuario_solicita,:usuario_aprueba,:usuario_registra,:permiso_aceptado,:horas_ocupadas)";
            $stmt = $pdo->prepare($consult_permisos);
            $stmt->bindParam(':id_usuarios', $id_usuarios, PDO::PARAM_INT);
            $stmt->bindParam(':provincia', $provincia, PDO::PARAM_STR);
            $stmt->bindParam(':regimen', $regimen, PDO::PARAM_STR);
            $stmt->bindParam(':coordinacion_zonal', $coordinacion_zonal, PDO::PARAM_STR);
            $stmt->bindParam(':direccion_unidad', $direccion_unidad, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_permiso', $fecha_permiso, PDO::PARAM_STR);
            $stmt->bindParam(':observaciones', $observaciones, PDO::PARAM_STR);
            $stmt->bindParam(':motivo_permiso', $motivo_permiso, PDO::PARAM_STR);
            $stmt->bindParam(':tiempo_motivo', $tiempoLimite_motivo, PDO::PARAM_STR);
            $stmt->bindParam(':desc_motivo', $desc_motivo, PDO::PARAM_STR);
            $stmt->bindParam(':dias_solicitados', $dias_solicitados, PDO::PARAM_STR);
            $stmt->bindParam(':dias_totales', $dias_totales, PDO::PARAM_STR);
            $stmt->bindParam(':horas_solicitadas', $horas_solicitadas2, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_permisos_desde', $fecha_permisos_desde, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_permiso_hasta', $fecha_permiso_hasta, PDO::PARAM_STR);
            $stmt->bindParam(':horas_permiso_desde', $horas_permiso_desde, PDO::PARAM_STR);
            $stmt->bindParam(':horas_permiso_hasta', $horas_permiso_hasta, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_solicita', $nombre_usuario, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_aprueba', $usuario_aprueba, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_registra', $usuario_registra, PDO::PARAM_STR);
            $stmt->bindParam(':permiso_aceptado', $permiso_aceptado, PDO::PARAM_STR);
            $stmt->bindParam(':horas_ocupadas', $horas_ocupadas, PDO::PARAM_STR);
            $stmt->execute();

            // Obtener el ID del último usuario insertado
            $id_usuario_insertado = $pdo->lastInsertId();
            $pdo->commit();
            create_flash_message(
                'Solicitud Ingresada Correctamente',
                'success'
            );

            redirect(RUTA_ABSOLUTA . "funcionario/solicitudUser");
        }
    } catch (PDOException $e) {

        $pdo->rollBack();
        return "Error de exepcion" . $e->getMessage();
    }
}

?>