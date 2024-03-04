<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php
    echo "Datos Individuales";
    ?>
    </title>
<?php
include_once "redirection.php";
include_once "funciones.php";
get_session();
if (!isset($_SESSION['id_usuarios'])) {
    redirect(RUTA_ABSOLUTA . 'logout');
}
$cedula = $_SESSION['cedula'];
$nombre = $_SESSION['nombres'];
$rol = $_SESSION['rol'];
?>
    <!-- Estilos del formulario -->
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <link href="https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet"></link>
</head>




<div class="container-fluid mt-5">
<?php
include_once "plantilla/hedeerDos.php";
include_once "conexion.php";


if ($_POST) {
    $id_permisos=$_POST["id_permisos"];

   $respuesta = vista_unica($id_permisos,$pdo);
}
    $numeroCambio = 1.36363636363636;
?>
<div class="row">
    <div class="col-10"></div>
    <div class="col-2 mb-2">
        <button href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="imprimirCertificado()">
            <i class="fas fa-download fa-sm text-white-50"></i>
             Generar Certificado
        </button>
    </div>
</div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Datos de la solicitud</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive" id="Solicitud_imprimir">
                <table class="responsive">
                    <?php
                        if (empty($respuesta)) {
                            echo "No existen datos en la tabla ";

                        }else {
                            $fecha_actual = date('Y-m-d');
                            foreach ($respuesta as $key => $valor){
                                $id_usuarios  = $valor ["id_usuarios"];
                                $id_permiso  = $valor ["id_permisos"];
                                $nombres  = $valor ["nombres"];
                                $apellidos  = $valor ["apellidos"];
                                $cedula  = $valor ["cedula"];
                                $provincia = $valor ["provincia"];
                                $regimen = $valor ["regimen"];
                                $coordinacion_zonal = $valor ["coordinacion_zonal"];
                                $direccion_unidad = $valor ["direccion_unidad"];
                                $fecha_permiso = $valor ["fecha_permiso"];
                                $motivo_permiso = $valor ["motivo_permiso"];
                                $motivo_permiso = str_replace('_', ' ', $motivo_permiso);
                                $tiempoLimite_motivo = $valor ["tiempo_motivo"];
                                $desc_motivo = $valor['desc_motivo'];
                                $dias_solicitados = $valor['dias_solicitados'];
                                $horas_solicitadas = (empty(strtotime($valor['horas_solicitadas'])) || $valor['horas_solicitadas'] == '00:00:00') ? "" : date('H:i', strtotime($valor['horas_solicitadas']));

                                $fecha_permisos_desde_formateada = $valor['fecha_permisos_desde'];
                                $fecha_permiso_hasta_formateada = $valor['fecha_permiso_hasta'];
                                $fecha_permisos_desde = ($fecha_permisos_desde_formateada == '0000-00-00') ? '' : date('d/m/Y', strtotime($fecha_permisos_desde_formateada));
                                $fecha_permiso_hasta = ($fecha_permiso_hasta_formateada == '0000-00-00') ? '' : date('d/m/Y', strtotime($fecha_permiso_hasta_formateada));

                                $horas_permiso_desde = $valor['horas_permiso_desde'];
                                $horas_permiso_hasta = $valor['horas_permiso_hasta'];
                                $usuario_solicita = $valor['usuario_solicita'];
                                $usuario_aprueba = $valor['usuario_aprueba'];

                                $usuario_registra = $valor['usuario_registra'];
                                $permiso_aceptado = $valor['permiso_aceptado'];
                                $observaciones = $valor['observaciones'];

                                function imprimirX($motivo, $texto){
                                    echo (str_replace(' ', '_', strtoupper($motivo)) == str_replace(' ', '_', strtoupper($texto))) ? "X" : "";
                                }

                            };
                        }

                        ?>
                    <tbody>
                        <tr>
                        <td class="column1 style167 s style169 text-center" colspan="11">
                            <div class="position-relative" style="display: flex; align-items: center; justify-content: space-between;">

                                <img style="width: 280px; height: 63px;" src="imagenes_defecto/Senescyt2.png" />

                                <div style="margin-right: 10px; text-align: center;">
                                    DIRECCIÓN DE TALENTO HUMANO<br />
                                    <span style="font-weight: bold; color: #000000; font-family: 'Tahoma'; font-size: 12pt;">
                                        FORMULARIO DE LICENCIAS Y PERMISOS
                                    </span>
                                </div>

                                <img style="margin-right: 30px;" src="imagenes_defecto/logo.png" />
                            </div>

                        </td>

                        </tr>
                        <tr class="row2">
                        <td class="column1 style173 s style174 text-center" colspan="2">FECHA</td>
                        <td class="column3 style204 null style205 text-center" colspan="2"><?= $fecha_actual ?></td>
                        <td class="column5 style173 s style174 text-left px-1" colspan="2">PROVINCIA</td>
                        <td class="column7 style206 null style208 text-center" colspan="3"><?= $provincia ?></td>
                        <td class="column10 style29 s px-1">REGIMEN</td>
                        <td class="column11 style38 null px-1"><?= $regimen ?></td>
                        </tr>
                        <tr class="row3">
                        <td class="column1 style106 s style108 text-center" colspan="11">
                            DATOS DEL SERVIDOR/TRABAJADOR
                        </td>
                        </tr>
                        <tr class="row4">
                        <td class="column10 style30 s px-1" colspan="3">
                            APELLIDOS Y NOMBRES:
                        </td>
                        <td class="column4 style199 null style201 px-1" colspan="6">
                            <?= $nombres . " " . $apellidos ?>
                        </td>
                        <td class="column10 style30 s px-1">CÉDULA DE CIUDADANÍA:</td>
                        <td class="column11 style92 null px-1"><?= $cedula ?></td>
                        </tr>
                        <tr class="row5">
                        <td class="column1 style202 null style203 text-center pb-2 pt-1" colspan="9">
                            <p class="text-left text-xs font-weight-bold mb-1 px-3">COORDINACION /GERENCIA /PROYECTO</p>
                            <?= $coordinacion_zonal ?>
                        </td>
                        <td class="column10 style202 null style203 text-center pb-2 pt-1" colspan="2">
                        <p class="text-left text-xs font-weight-bold mb-1 px-3">DIRECCION O UNIDAD</p>
                            <?= $direccion_unidad ?>
                        </td>
                        </tr>
                        <tr class="row6">
                        <td class="column1 style154 s style156 text-center" colspan="9">MOTIVO</td>
                        <td class="column10 style115 s style116 text-center" colspan="2">
                            FECHA DEL PERMISO
                        </td>
                        </tr>
                        <tr class="row7">
                        <!-- Aqui se tiene que marcar el motivo del permiso -->
                        <td class="column1 style67 null"><?= imprimirX($motivo_permiso, "LICENCIA_POR_CALAMIDAD_DOMESTICA"); ?></td>
                        <td class="column2 style157 s style159 px-1" colspan="3">
                            LICENCIA POR CALAMIDAD DOMÉSTICA
                        </td>
                        <td class="column5 style68 null"><?= imprimirX($motivo_permiso, "PERMISO_PARA_ESTUDIOS_REGULARES"); ?></td>
                        <td class="column6 style157 s style159 px-1" colspan="4">
                            PERMISO PARA ESTUDIOS REGULARES
                        </td>
                        <td class="column10 style5 s text-center">DESDE (dd/mm/aaaa)</td>
                        <td class="column11 style6 s text-center">HASTA (dd/mm/aaaa)</td>
                        </tr>
                        <tr class="row8">
                        <td class="column1 style69 null"><?= imprimirX($motivo_permiso, "LICENCIA_POR_ENFERMEDAD"); ?></td>
                        <td class="column2 style135 s style137 px-1" colspan="3">
                            LICENCIA POR ENFERMEDAD
                        </td>
                        <td class="column5 style70 null"><?= imprimirX($motivo_permiso, "PERMISO_DE_DIAS_CON_CARGO_A_VACACIONES"); ?></td>
                        <td class="column6 style135 s style137 px-1" colspan="4">
                            PERMISO DE DÍAS CON CARGO A VACACIONES
                        </td>
                        <td class="column10 style71 null text-center"><?= $fecha_permisos_desde ?></td>
                        <td class="column11 style71 null text-center"><?= $fecha_permiso_hasta ?></td>
                        </tr>
                        <tr class="row9">
                        <td class="column1 style69 null"><?= imprimirX($motivo_permiso, "LICENCIA_POR_MATERNIDAD"); ?></td>
                        <td class="column2 style135 s style137 px-1" colspan="3">
                            LICENCIA POR MATERNIDAD
                        </td>
                        <td class="column5 style70 null"><?= imprimirX($motivo_permiso, "PERMISO_POR_ASUNTOS_OFICIALES"); ?></td>
                        <td class="column6 style135 s style137 px-1" colspan="4">
                            <a class="comment-indicator"></a>
                            <div class="comment">
                            TTHH:<br />
                            Siempre se debera colocar en observaciones el motivo del permiso y
                            el lugar en donde se realizo el evento institucional
                            </div>
                            PERMISO POR ASUNTOS OFICIALES
                        </td>
                        <td class="column10 style150 s style151 text-center" colspan="2">
                            EN CASO DE HORAS
                        </td>
                        </tr>
                        <tr class="row10">
                        <td class="column1 style69 null"><?= imprimirX($motivo_permiso, "LICENCIA_POR_MATRIMONIO_O_UNION_DE_ECHO"); ?></td>
                        <td class="column2 style135 s style137 px-1" colspan="3">
                            LICENCIA POR MATRIMONIO O UNIÓN DE HECHO
                        </td>
                        <td class="column5 style70 null"><?= imprimirX($motivo_permiso, "PERMISO_PARA_ATENCION_MEDICA"); ?></td>
                        <td class="column6 style135 s style137 px-1" colspan="4">
                            PERMISO PARA ATENCIÓN MÉDICA
                        </td>
                        <td class="column10 style6 s text-center">DESDE (hh:mm)</td>
                        <td class="column11 style6 s text-center">HASTA (hh:mm)</td>
                        </tr>
                        <tr class="row11">
                        <td class="column1 style69 null"><?= imprimirX($motivo_permiso, "LICENCIA_POR_PATERNIDAD"); ?></td>
                        <td class="column2 style135 s style137 px-1" colspan="3">
                            LICENCIA POR PATERNIDAD
                        </td>
                        <td class="column5 style70 null"><?= imprimirX($motivo_permiso, "OTROS"); ?></td>
                        <td class="column6 style135 s style137 px-1" colspan="4">OTROS</td>
                        <td class="column10 style12 null text-center"><?= $horas_permiso_desde ?></td>
                        <td class="column11 style12 null text-center"><?= $horas_permiso_hasta ?></td>
                        </tr>
                        <tr class="row12">
                        <td class="column1 style114 s style116 text-center" colspan="9">
                            OBSERVACIONES O JUSTIFICATIVOS
                        </td>
                        <td class="column10 style117 s style118 text-center" colspan="2">
                            VALOR A DESCONTAR<br />
                            DÍAS | HORAS
                        </td>
                        </tr>
                        <tr class="row13">
                        <td class="column1 style190 null style198 px-1" colspan="9"rowspan="3"><?= $observaciones ?></td>
                        <td class="column10 style15 f text-center"><?= $dias_solicitados ?></td>
                        <td class="column11 style14 f text-center"><?= $horas_solicitadas ?></td>
                        </tr>
                        <tr class="row14">
                        <td class="column10 style128 f style129 text-center" colspan="2">
                        <?php
                            if (!empty($horas_solicitadas)) {
                                $valor_mostrar = $horas_solicitadas;
                                $xMultiplicar = 0;
                            } elseif (!empty($dias_solicitados)) {
                                $valor_mostrar = $dias_solicitados;
                                $xMultiplicar = $valor_mostrar * $numeroCambio;
                                $xMultiplicar = substr((string)$xMultiplicar, 0, 4);
                            }

                            echo $valor_mostrar . ' DÍAS SOLICITADOS / ' . $xMultiplicar . ' VALOR A DESCONTAR EL TIEMPO SOLICITADO SE MULTIPLICA POR 1,36363636363636';
                        ?>

                        </td>
                        </tr>
                        <tr class="row15">
                        <td class="column10 style130 f style131 px-1" colspan="2">
                        <?=  $tiempoLimite_motivo ?>
                        </td>
                        </tr>
                        <tr class="row16">
                        <td class="column1 style132 s style134 text-center" colspan="4">SOLICITA</td>
                        <td class="column5 style132 s style134 text-center" colspan="5">APRUEBA</td>
                        <td class="column10 style132 s style134 text-center" colspan="2">REGISTRA</td>
                        </tr>
                        <tr class="row17">
                        <td class="column10 style130 f style131 text-center" colspan="4">
                        <?=  $nombres . " " . $apellidos ?>
                        </td>
                        <td class="column5 style184 null style183 text-center" colspan="5">
                        <?=  $usuario_aprueba ?>
                        </td>
                        <td class="column10 style130 f style131 text-center" colspan="2">
                        <?=  $usuario_registra ?>
                        </td>
                        </tr>
                        <tr class="row18">
                        <td class="column1 style111 s style113 text-center" colspan="4">
                            Servidor/Trabajador
                        </td>
                        <td class="column5 style112 s style113 text-center" colspan="5">
                            Jefe Inmediato
                        </td>
                        <td class="column10 style112 s style113 text-center" colspan="2">
                            Talento Humano
                        </td>
                        </tr>
                        <tr class="row19">
                        <td class="column1 style97 s style99 text-center" colspan="3">TIPO DE PERMISO</td>
                        <td class="column4 style97 s style99 text-center" colspan="8">DESCRIPCIÓN</td>
                        </tr>
                        <tr class="row20">
                        <td class="column1 style100 f style102 text-center" colspan="3">
                        <?=  $motivo_permiso ?>
                        </td>
                        <td class="column4 style103 f style105 text-center" colspan="8">
                        <?=  $desc_motivo ?>
                        </td>
                        </tr>
                        <tr class="row21">
                        <td class="column1 style106 s style108" colspan="11">
                            Todo formulario de permiso / licencia, deberá ser presentado a la
                            Dirección de Talento Humano con su respectiva justificación, máximo
                            en los tres días posteriores a la emisión del mismo, caso contrario
                            el formulario será nulo y se descontará directamente de vacaciones.
                        </td>
                        </tr>

                        <td class="text-center p-2" colspan="14">-----------------------------------------------------------------------------------------------------------------------------------------------------</td>

                        <tr>
                        <td class="column1 style167 s style169 text-center" colspan="11">
                            <div class="position-relative" style="display: flex; align-items: center; justify-content: space-between;">

                                <img style="width: 280px; height: 63px;" src="imagenes_defecto/Senescyt2.png" />

                                <div style="margin-right: 10px; text-align: center;">
                                    DIRECCIÓN DE TALENTO HUMANO<br />
                                    <span style="font-weight: bold; color: #000000; font-family: 'Tahoma'; font-size: 12pt;">
                                        FORMULARIO DE LICENCIAS Y PERMISOS
                                    </span>
                                </div>

                                <img style="margin-right: 30px;" src="imagenes_defecto/logo.png" />
                            </div>

                        </td>

                        </tr>
                        <tr class="row2">
                        <td class="column1 style173 s style174 text-center" colspan="2">FECHA</td>
                        <td class="column3 style204 null style205 text-center" colspan="2"><?= $fecha_actual ?></td>
                        <td class="column5 style173 s style174 text-left px-1" colspan="2">PROVINCIA</td>
                        <td class="column7 style206 null style208 text-center" colspan="3"><?= $provincia ?></td>
                        <td class="column10 style29 s px-1">REGIMEN</td>
                        <td class="column11 style38 null px-1"><?= $regimen ?></td>
                        </tr>
                        <tr class="row3">
                        <td class="column1 style106 s style108 text-center" colspan="11">
                            DATOS DEL SERVIDOR/TRABAJADOR
                        </td>
                        </tr>
                        <tr class="row4">
                        <td class="column10 style30 s px-1" colspan="3">
                            APELLIDOS Y NOMBRES:
                        </td>
                        <td class="column4 style199 null style201 px-1" colspan="6">
                            <?= $nombres . " " . $apellidos ?>
                        </td>
                        <td class="column10 style30 s px-1">CÉDULA DE CIUDADANÍA:</td>
                        <td class="column11 style92 null px-1"><?= $cedula ?></td>
                        </tr>
                        <tr class="row5">
                        <td class="column1 style202 null style203 text-center pb-2 pt-1" colspan="9">
                            <p class="text-left text-xs font-weight-bold mb-1 px-3">COORDINACION /GERENCIA /PROYECTO</p>
                            <?= $coordinacion_zonal ?>
                        </td>
                        <td class="column10 style202 null style203 text-center pb-2 pt-1" colspan="2">
                        <p class="text-left text-xs font-weight-bold mb-1 px-3">DIRECCION O UNIDAD</p>
                            <?= $direccion_unidad ?>
                        </td>
                        </tr>
                        <tr class="row6">
                        <td class="column1 style154 s style156 text-center" colspan="9">MOTIVO</td>
                        <td class="column10 style115 s style116 text-center" colspan="2">
                            FECHA DEL PERMISO
                        </td>
                        </tr>
                        <tr class="row7">
                        <!-- Aqui se tiene que marcar el motivo del permiso -->
                        <td class="column1 style67 null"><?= imprimirX($motivo_permiso, "LICENCIA_POR_CALAMIDAD_DOMESTICA"); ?></td>
                        <td class="column2 style157 s style159 px-1" colspan="3">
                            LICENCIA POR CALAMIDAD DOMÉSTICA
                        </td>
                        <td class="column5 style68 null"><?= imprimirX($motivo_permiso, "PERMISO_PARA_ESTUDIOS_REGULARES"); ?></td>
                        <td class="column6 style157 s style159 px-1" colspan="4">
                            PERMISO PARA ESTUDIOS REGULARES
                        </td>
                        <td class="column10 style5 s text-center">DESDE (dd/mm/aaaa)</td>
                        <td class="column11 style6 s text-center">HASTA (dd/mm/aaaa)</td>
                        </tr>
                        <tr class="row8">
                        <td class="column1 style69 null"><?= imprimirX($motivo_permiso, "LICENCIA_POR_ENFERMEDAD"); ?></td>
                        <td class="column2 style135 s style137 px-1" colspan="3">
                            LICENCIA POR ENFERMEDAD
                        </td>
                        <td class="column5 style70 null"><?= imprimirX($motivo_permiso, "PERMISO_DE_DIAS_CON_CARGO_A_VACACIONES"); ?></td>
                        <td class="column6 style135 s style137 px-1" colspan="4">
                            PERMISO DE DÍAS CON CARGO A VACACIONES
                        </td>
                        <td class="column10 style71 null text-center"><?= $fecha_permisos_desde ?></td>
                        <td class="column11 style71 null text-center"><?= $fecha_permiso_hasta ?></td>
                        </tr>
                        <tr class="row9">
                        <td class="column1 style69 null"><?= imprimirX($motivo_permiso, "LICENCIA_POR_MATERNIDAD"); ?></td>
                        <td class="column2 style135 s style137 px-1" colspan="3">
                            LICENCIA POR MATERNIDAD
                        </td>
                        <td class="column5 style70 null"><?= imprimirX($motivo_permiso, "PERMISO_POR_ASUNTOS_OFICIALES"); ?></td>
                        <td class="column6 style135 s style137 px-1" colspan="4">
                            <a class="comment-indicator"></a>
                            <div class="comment">
                            TTHH:<br />
                            Siempre se debera colocar en observaciones el motivo del permiso y
                            el lugar en donde se realizo el evento institucional
                            </div>
                            PERMISO POR ASUNTOS OFICIALES
                        </td>
                        <td class="column10 style150 s style151 text-center" colspan="2">
                            EN CASO DE HORAS
                        </td>
                        </tr>
                        <tr class="row10">
                        <td class="column1 style69 null"><?= imprimirX($motivo_permiso, "LICENCIA_POR_MATRIMONIO_O_UNION_DE_ECHO"); ?></td>
                        <td class="column2 style135 s style137 px-1" colspan="3">
                            LICENCIA POR MATRIMONIO O UNIÓN DE HECHO
                        </td>
                        <td class="column5 style70 null"><?= imprimirX($motivo_permiso, "PERMISO_PARA_ATENCION_MEDICA"); ?></td>
                        <td class="column6 style135 s style137 px-1" colspan="4">
                            PERMISO PARA ATENCIÓN MÉDICA
                        </td>
                        <td class="column10 style6 s text-center">DESDE (hh:mm)</td>
                        <td class="column11 style6 s text-center">HASTA (hh:mm)</td>
                        </tr>
                        <tr class="row11">
                        <td class="column1 style69 null"><?= imprimirX($motivo_permiso, "LICENCIA_POR_PATERNIDAD"); ?></td>
                        <td class="column2 style135 s style137 px-1" colspan="3">
                            LICENCIA POR PATERNIDAD
                        </td>
                        <td class="column5 style70 null"><?= imprimirX($motivo_permiso, "OTROS"); ?></td>
                        <td class="column6 style135 s style137 px-1" colspan="4">OTROS</td>
                        <td class="column10 style12 null text-center"><?= $horas_permiso_desde ?></td>
                        <td class="column11 style12 null text-center"><?= $horas_permiso_hasta ?></td>
                        </tr>
                        <tr class="row12">
                        <td class="column1 style114 s style116 text-center" colspan="9">
                            OBSERVACIONES O JUSTIFICATIVOS
                        </td>
                        <td class="column10 style117 s style118 text-center" colspan="2">
                            VALOR A DESCONTAR<br />
                            DÍAS | HORAS
                        </td>
                        </tr>
                        <tr class="row13">
                        <td class="column1 style190 null style198 px-1" colspan="9"rowspan="3"><?= $observaciones ?></td>
                        <td class="column10 style15 f text-center"><?= $dias_solicitados ?></td>
                        <td class="column11 style14 f text-center"><?= $horas_solicitadas ?></td>
                        </tr>
                        <tr class="row14">
                        <td class="column10 style128 f style129 text-center" colspan="2">
                        <?php
                            if (!empty($horas_solicitadas)) {
                                $valor_mostrar = $horas_solicitadas;
                                $xMultiplicar = 0;
                            } elseif (!empty($dias_solicitados)) {
                                $valor_mostrar = $dias_solicitados;
                                $xMultiplicar = $valor_mostrar * $numeroCambio;
                                $xMultiplicar = substr((string)$xMultiplicar, 0, 4);
                            }

                            echo $valor_mostrar . ' DÍAS SOLICITADOS / ' . $xMultiplicar . ' VALOR A DESCONTAR EL TIEMPO SOLICITADO SE MULTIPLICA POR 1,36363636363636';
                        ?>

                        </td>
                        </tr>
                        <tr class="row15">
                        <td class="column10 style130 f style131 px-1" colspan="2">
                        <?=  $tiempoLimite_motivo ?>
                        </td>
                        </tr>
                        <tr class="row16">
                        <td class="column1 style132 s style134 text-center" colspan="4">SOLICITA</td>
                        <td class="column5 style132 s style134 text-center" colspan="5">APRUEBA</td>
                        <td class="column10 style132 s style134 text-center" colspan="2">REGISTRA</td>
                        </tr>
                        <tr class="row17">
                        <td class="column10 style130 f style131 text-center p-3" colspan="4">

                        </td>
                        <td class="column5 style184 null style183 text-center" colspan="5">

                        </td>
                        <td class="column10 style130 f style131 text-center" colspan="2">

                        </td>
                        </tr>
                        <tr class="row18">
                        <td class="column1 style111 s style113 text-center" colspan="4">
                            Servidor/Trabajador
                        </td>
                        <td class="column5 style112 s style113 text-center" colspan="5">
                            Jefe Inmediato
                        </td>
                        <td class="column10 style112 s style113 text-center" colspan="2">
                            Talento Humano
                        </td>
                        </tr>
                        <tr class="row19">
                        <td class="column1 style97 s style99 text-center" colspan="3">TIPO DE PERMISO</td>
                        <td class="column4 style97 s style99 text-center" colspan="8">DESCRIPCIÓN</td>
                        </tr>
                        <tr class="row20">
                        <td class="column1 style100 f style102 text-center" colspan="3">
                        <?=  $motivo_permiso ?>
                        </td>
                        <td class="column4 style103 f style105 text-center" colspan="8">
                        <?=  $desc_motivo ?>
                        </td>
                        </tr>
                        <tr class="row21">
                        <td class="column1 style106 s style108" colspan="11">
                            Todo formulario de permiso / licencia, deberá ser presentado a la
                            Dirección de Talento Humano con su respectiva justificación, máximo
                            en los tres días posteriores a la emisión del mismo, caso contrario
                            el formulario será nulo y se descontará directamente de vacaciones.
                        </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<script>
    function imprimirCertificado() {
        var d = new Date();
        var strDate = (d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate() +
            " a las " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2));
        printJS({
            printable:"Solicitud_imprimir",
            type:"html",
            css:["css/personal/estilo_imprimir.css","css/personal/styles.css","css/sb-admin-2.css","css/sb-admin-2.min.css"],
        })
    }
</script>
<?php include_once("plantilla/footer.php")?>
