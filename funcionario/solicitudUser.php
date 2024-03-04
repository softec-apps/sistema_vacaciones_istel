<?php
include_once "../redirection.php";
include_once "../flash_messages.php";
 session_start();

 if (!isset($_SESSION['id_usuarios'])) {

    redirect(RUTA_ABSOLUTA . "logout");
 }
$id = $_SESSION['id_usuarios'];
$cedula = $_SESSION['cedula'];
$nombreFuncionario = $_SESSION['nombres'];
$apellidosFuncionario = $_SESSION['apellidos'];
$rol = $_SESSION['rol'];
$fecha_ingreso = $_SESSION['fecha_ingreso'];

if ($rol != 'Funcionario') {

    redirect(RUTA_ABSOLUTA . "logout");
}

$message = '';
$type = '';
$flash_message = display_flash_message();

if (isset($flash_message)) {
    $message = $flash_message['message'];
    $type = $flash_message['type'];
}

$titulo = "Usuarios o funcionarios";
include_once("../plantilla/header.php")
?>

<div class="container-fluid mt-5">
<?php

?>
<?php

include_once  "funcionesCalcular.php";
foreach ($nuevosDatos as $valor) {
    $nombres_usuario =  $valor['nombres'];
    $apellidos_usuario = $valor['apellidos'];
    $cedula_usuario = $valor['cedula'];
    $horasOcupadasMultiplicadas = $valor['horas_ocupadas'];
    $permiso_aceptado = $valor['permiso_aceptado'];
    $tiempo_trabajo = $valor['tiempo_trabajo'];

}

// Obtener el mensaje de días de vacaciones y permisos
if (!empty($nuevosDatos)) {
    // Obtener el mensaje de días de vacaciones y permisos
    $mensaje = obtenerMensajeDiasVacaciones($id, $nombreFuncionario, $apellidosFuncionario, $limiteVacaciones, $diasPorAnoTrabajado, $diasPorAno, $pdo, $tiempo_trabajo);
} else {
    // En caso de que $nuevosDatos esté vacía, asignar un valor por defecto o tomar alguna otra acción si es necesario
    $mensaje = "No Existen solicitudes Realizadas";
}

    $nombreUser = $nombreFuncionario . " " . $apellidosFuncionario;
?>

                    <h5 class="text-gray-800">Todos los permisos realizados</h5>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header d-flex py-3">
                            <p class="m-2 col-10 pl-0 font-weight-bold text-primary">
                                <?php echo$mensaje; ?>
                            </p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive crud-table">
                                <table class="table table-bordered" id="tabla_vacaciones_funcionarios">
                                    <thead>
                                        <tr>
                                            <th>Tipo de permiso </th>
                                            <th>Días solicitados</th>
                                            <th>Horas solicitadas</th>
                                            <th>Descuento de días de vacaciones</th>
                                            <th class="exclude">Solicitud</th>
                                            <th class="exclude">Archivos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($nuevosDatos as $valor) {
                                            $id_permisos =  $valor['id_permisos'];
                                            $nombres_usuario =  $valor['nombres'];
                                            $apellidos_usuario = $valor['apellidos'];
                                            $cedula_usuario = $valor['cedula'];
                                            $horasOcupadasMultiplicadas = $valor['horas_ocupadas'] ;
                                            $dias_solicitados = $valor['dias_solicitados'];
                                            $horas_solicitadas = $valor['horas_solicitadas'];
                                            $tiempo_trabajo = $valor['tiempo_trabajo'];
                                            $horas_formateadas = date('H', strtotime($horas_solicitadas));
                                            $motivo_permiso = $valor['motivo_permiso'];
                                            $motivo_permiso = str_replace('_', ' ', $motivo_permiso);


                                            if ($horasOcupadasMultiplicadas >= 8) {
                                                $diasSolicitados = $horasOcupadasMultiplicadas / $tiempo_trabajo;
                                                $horasOcupadasMultiplicadas = 0;
                                                $tipo_solicitud = "Si";
                                            }elseif ($horasOcupadasMultiplicadas == 0) {
                                                $diasSolicitados = $dias_solicitados;
                                                $horasOcupadasMultiplicadas = $horas_formateadas;
                                                $tipo_solicitud = "No";
                                            }elseif($horasOcupadasMultiplicadas < 8) {
                                                $diasSolicitados = 0;
                                                $horasOcupadasMultiplicadas = $horasOcupadasMultiplicadas;
                                                $tipo_solicitud = "Si";
                                            }
                                            $permiso_aceptado = $valor['permiso_aceptado'];
                                            $ruta_solicita = $valor ["ruta_solicita"];
                                            $ruta_aprueba = $valor['ruta_aprueba'];
                                            $ruta_registra = $valor['ruta_registra'];

                                            if (!empty($ruta_solicita) && $permiso_aceptado == 0) {
                                                $ruta_solicita = '<a class="btn btn-warning m-1" title="Solicitud firmada por el funcionario" href="' . RUTA_ABSOLUTA . $ruta_solicita .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';
                                                $ruta_aprueba = null;

                                                $ruta_registra = null;
                                                $permisoAceptadoClass ="bg-primary text-white text-center";
                                                $title = "Solicitud en proceso";
                                                $icono = '<i class="fa-lg fa-solid fa-sync fa-spin mt-3"></i>';

                                                $archivo = null;
                                                $eliminar = null;
                                            }elseif ($permiso_aceptado == 2) {

                                                $ruta_solicita = '<a class="btn btn-warning m-1" title="Solicitud firmada por el funcionario" href="' . RUTA_ABSOLUTA . $ruta_solicita .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';
                                                $ruta_aprueba = null;
                                                $ruta_registra = null;

                                                $permisoAceptadoClass ="bg-danger text-white text-center";
                                                $title = "Solicitud rechazada";
                                                $icono = '<i class="fa-lg fa-solid fa-xmark mt-3"></i>';
                                                $archivo = null;
                                                $eliminar = null;
                                            }elseif ($permiso_aceptado == 1) {

                                                $ruta_solicita = '<a class="btn btn-warning m-1" title="Solicitud firmada por el funcionario" href="' . RUTA_ABSOLUTA . $ruta_solicita .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                                $ruta_aprueba = '<a class="btn btn-success m-1" title="Solicitud firmada por el jefe" href="' . RUTA_ABSOLUTA . $ruta_aprueba .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                                $ruta_registra = null;

                                                $icono = '<i class="fa-lg fa-solid fa-check mt-3"></i>';
                                                $permisoAceptadoClass ="bg-success text-white text-center";
                                                $title = "Solicitud aprobada";

                                                $archivo = null;

                                                $eliminar = null;
                                            }elseif ($permiso_aceptado == 3) {
                                                $icono = '<i class="fa-lg fa-solid fa-check mt-3"></i>';
                                                $permisoAceptadoClass ="bg-dark text-white text-center";
                                                $title = "Solicitud registrada por talento humano";

                                                $ruta_solicita = '<a class="btn btn-warning m-1" title="Solicitud firmada por el funcionario" href="' . RUTA_ABSOLUTA . $ruta_solicita .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                                $ruta_aprueba = '<a class="btn btn-success m-1" title="Solicitud firmada por el jefe" href="' . RUTA_ABSOLUTA . $ruta_aprueba .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                                $ruta_registra = '<a class="btn btn-dark m-1" title="Solicitud registrada por talento humano" href="' . RUTA_ABSOLUTA . $ruta_registra .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                                $archivo = null;

                                                $eliminar = null;
                                            }elseif ($permiso_aceptado == 0) {
                                                $permisoAceptadoClass ="bg-primary text-white text-center";
                                                $title = "Solicitud en proceso";
                                                $icono = '<button class="btn btn-primary m-1" title="Subir archivo" data-toggle="modal"
                                                data-target="#subirEscaneado" data-id="'. $id_permisos .'" onclick="subirArchivo(this)" >
                                                <i class="fa-lg fa-solid fa-file-arrow-up"></i>
                                                </button>';

                                                $archivo =  null;

                                                $eliminar = '<button class="btn btn-danger m-1" title="Eliminar" data-toggle="modal"
                                                data-target="#Eliminar" data-id="'. $id_permisos .'" onclick="eliminar(this)">
                                                <i class="fas fa-trash"></i>
                                                </button>';
                                            }

                                        ?>
                                        <tr>
                                            <td>
                                                <?= $motivo_permiso?>
                                            </td>

                                            <td>
                                            <?= $diasSolicitados?>
                                            </td>

                                            <td>
                                            <?= $horasOcupadasMultiplicadas?>
                                            </td>

                                            <td>
                                            <?= $tipo_solicitud?>
                                            </td>

                                            <td class="<?= $permisoAceptadoClass?>" title="<?= $title; ?>">
                                                <?= $icono?>
                                            </td>

                                            <td class="d-flex">
                                                <form action="../datos_individuales" method="POST" class="d-inline-block m-1">
                                                    <input type="hidden" name="id_permisos" value="<?= $id_permisos?> ">
                                                    <button class="btn btn-info m-1" title="Ver solicitud aprobada">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </form>
                                            <?= $ruta_solicita?>
                                            <?= $ruta_aprueba?>
                                            <?= $ruta_registra?>
                                            <?= $archivo?>
                                            <?= $eliminar?>
                                            </td>


                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

<!-- Modal Eliminar -->
<div class="modal fade" id="Eliminar" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdminLabel">Confirmar eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar este registro de un permiso?</p>
                <form id="eliminarForm" action="<?php echo RUTA_ABSOLUTA ?>funcionario/eliminar" method="post">
                    <input type="hidden" name="id_permiso" id="id_permiso" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="eliminarForm" class="btn btn-danger">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para solicitar permisos -->
<div class="modal fade" id="registrar_vacaciones" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdminLabel">Solicitar permiso </h5>
                <button type="button" class="close cerrarModal" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo RUTA_ABSOLUTA; ?>funcionario/solicitarPermiso" id="solicitud_permiso_form" method="post">
                    <input type="hidden" name="nombre_usuario" value="<?= $nombreUser?>">
                    <input type="hidden" name="id_usuario" value="<?php echo $id; ?>">

                    <div class="row mb-3">
                        <div class="col">
                            <select class="form-select" name="regimen" required >
                                <option value="" disabled selected>Tipo de regimen</option>
                                <option value="LOSEP">LOSEP</option>
                                <option value="CODIGO DEL TRABAJO">CODIGO DEL TRABAJO</option>
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-select" name="motivo" id="motivo" required>
                                <option value="" disabled selected>Motivo del permiso</option>
                                <option value="LICENCIA_POR_CALAMIDAD_DOMESTICA">Licencia por calamidad domestica</option>
                                <option value="LICENCIA_POR_ENFERMEDAD">Licencia por enfermedad</option>
                                <option value="LICENCIA_POR_MATERNIDAD">Licencia por maternidad</option>
                                <option value="LICENCIA_POR_MATRIMONIO_O_UNION_DE_ECHO">Licencia por matrimonio o union de echo</option>
                                <option value="LICENCIA_POR_PATERNIDAD">Licencia por paternidad</option>
                                <option value="PERMISO_PARA_ESTUDIOS_REGULARES">Permiso para estudios regulares</option>
                                <option value="PERMISO_DE_DIAS_CON_CARGO_A_VACACIONES">Permisos de dias con cargo a vacaciones</option>
                                <!-- <option value="PERMISO_POR_ASUNTOS_OFICIALES">Permiso por asuntos oficales</option> -->
                                <option value="PERMISO_PARA_ATENCION_MEDICA">Permiso para atencion medica</option>
                                <option value="OTROS">Otros</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col" id="div_fecha_inicio">
                            <input class="form-control" type="text" name="fecha_inicio" placeholder="Fecha de Inicio" id="fecha_inicio" onfocus="(this.type='date')" onblur="(this.type='text')" required/>
                        </div>
                        <div class="col" id="div_fecha_fin">
                            <input class="form-control" type="text" name="fecha_fin" placeholder="Fecha Fin" id="fecha_fin" onfocus="(this.type='date')" onblur="(this.type='text')" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col" id="div_hora_inicio">
                            <input class="form-control" type="text" name="hora_inicio" placeholder="Hora de Inicio" id="hora_inicio" onfocus="(this.type='time')" onblur="(this.type='text')" required/>
                        </div>
                        <div class="col" id="div_hora_fin">
                            <input class="form-control" placeholder="Hora Fin" type="text" name="hora_fin" placeholder="Hora Fin" id="hora_fin" onfocus="(this.type='time')" onblur="(this.type='text')"/>
                        </div>
                    </div>

                    <input class="form-control" type="hidden" name="permiso_aceptado" value="0" />

                    <div class="row mb-3">
                        <div class="col">
                            <textarea class="form-control" name="observaciones" cols="30" rows="4" placeholder="Observaciones o Justificativos"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Solicitar permiso</button>
                        <button type="button" class="btn btn-secondary cerrarModal" data-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(".cerrarModal").click(function(){
        $("#registrar_vacaciones").modal('hide');
    });
    function eliminar(button) {
        var userId = button.getAttribute('data-id');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('id_permiso').value = userId;
    }
    function subirArchivo(button) {
        var permisoId = button.getAttribute('data-id');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('id_permisoSubir').value = permisoId;
    }
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

    function actualizarCampos() {
    var fechaInicio = document.getElementById("fecha_inicio");
    var fechaFin = document.getElementById("fecha_fin");
    var horaInicio = document.getElementById("hora_inicio");
    var horaFin = document.getElementById("hora_fin");

    // Obtener el valor seleccionado en el select
    var motivoSeleccionado = document.getElementById("motivo").value;

    // Obtener los divs que contienen los inputs
    var divFechaInicio = document.getElementById("div_fecha_inicio");
    var divFechaFin = document.getElementById("div_fecha_fin");
    var divHoraInicio = document.getElementById("div_hora_inicio");
    var divHoraFin = document.getElementById("div_hora_fin");

    // Restablecer la visibilidad de todos los divs
    divFechaInicio.style.display = "block";
    divFechaFin.style.display = "block";
    divHoraInicio.style.display = "block";
    divHoraFin.style.display = "block";

    // Restablecer los atributos required de todos los campos
    fechaInicio.setAttribute("required", "required");
    fechaFin.setAttribute("required", "required");
    horaInicio.setAttribute("required", "required");
    horaFin.setAttribute("required", "required");

    switch (motivoSeleccionado) {
        case "LICENCIA_POR_CALAMIDAD_DOMESTICA":
            // Ocultar secciones de horas
            divHoraInicio.style.display = "none";
            divHoraFin.style.display = "none";
            horaInicio.removeAttribute("required");
            horaFin.removeAttribute("required");
            // Borrar datos de horas
            horaInicio.value = "";
            horaFin.value = "";
            break;
        case "LICENCIA_POR_ENFERMEDAD":
            // Ocultar secciones de horas
            divHoraInicio.style.display = "none";
            divHoraFin.style.display = "none";
            horaInicio.removeAttribute("required");
            horaFin.removeAttribute("required");
            // Borrar datos de horas
            horaInicio.value = "";
            horaFin.value = "";
            break;
        case "LICENCIA_POR_MATERNIDAD":
            // Ocultar secciones de horas
            divHoraInicio.style.display = "none";
            divHoraFin.style.display = "none";
            horaInicio.removeAttribute("required");
            horaFin.removeAttribute("required");
            // Borrar datos de horas
            horaInicio.value = "";
            horaFin.value = "";
            break;

            case "LICENCIA_POR_MATRIMONIO_O_UNION_DE_ECHO":
            // Ocultar secciones de horas
            divHoraInicio.style.display = "none";
            divHoraFin.style.display = "none";
            horaInicio.removeAttribute("required");
            horaFin.removeAttribute("required");
            // Borrar datos de horas
            horaInicio.value = "";
            horaFin.value = "";
            break;
        case "LICENCIA_POR_PATERNIDAD":
            // Ocultar secciones de horas
            divHoraInicio.style.display = "none";
            divHoraFin.style.display = "none";
            horaInicio.removeAttribute("required");
            horaFin.removeAttribute("required");
            // Borrar datos de horas
            horaInicio.value = "";
            horaFin.value = "";
            break;
        case "PERMISO_PARA_ESTUDIOS_REGULARES":
            //Ocultar secciones de fechas
            divFechaInicio.style.display = "none";
            divFechaFin.style.display = "none";
            fechaInicio.removeAttribute("required");
            fechaFin.removeAttribute("required");
            // Borrar datos de fechas
            fechaInicio.value = "";
            fechaFin.value = "";
            break;
        case "PERMISO_DE_DIAS_CON_CARGO_A_VACACIONES":
            // Ocultar secciones de horas
            divHoraInicio.style.display = "none";
            divHoraFin.style.display = "none";
            horaInicio.removeAttribute("required");
            horaFin.removeAttribute("required");
            // Borrar datos de horas
            horaInicio.value = "";
            horaFin.value = "";
            break;
        case "PERMISO_POR_ASUNTOS_OFICIALES":
            // Si se elige una hora, ocultar las secciones de fecha
            if (horaInicio.value !== "" || horaFin.value !== "") {
                divFechaInicio.style.display = "none";
                divFechaFin.style.display = "none";
                fechaInicio.removeAttribute("required");
                fechaFin.removeAttribute("required");
                // Borrar datos de fechas
                fechaInicio.value = "";
                fechaFin.value = "";
            }

            // Si se elige una fecha, ocultar las secciones de hora
            else if (fechaInicio.value !== "" || fechaFin.value !== "") {
                divHoraInicio.style.display = "none";
                divHoraFin.style.display = "none";
                horaInicio.removeAttribute("required");
                horaFin.removeAttribute("required");
                // Borrar datos de horas
                horaInicio.value = "";
                horaFin.value = "";
            }
            break;
        case "PERMISO_PARA_ATENCION_MEDICA":
            //Ocultar secciones de fechas
            divFechaInicio.style.display = "none";
            divFechaFin.style.display = "none";
            fechaInicio.removeAttribute("required");
            fechaFin.removeAttribute("required");
            // Borrar datos de fechas
            fechaInicio.value = "";
            fechaFin.value = "";
            break;
        case "OTROS":
            // Si se elige una hora, ocultar las secciones de fecha
            if (horaInicio.value !== "" || horaFin.value !== "") {
                divFechaInicio.style.display = "none";
                divFechaFin.style.display = "none";
                fechaInicio.removeAttribute("required");
                fechaFin.removeAttribute("required");
                // Borrar datos de fechas
                fechaInicio.value = "";
                fechaFin.value = "";
            }

            // Si se elige una fecha, ocultar las secciones de hora
            else if (fechaInicio.value !== "" || fechaFin.value !== "") {
                divHoraInicio.style.display = "none";
                divHoraFin.style.display = "none";
                horaInicio.removeAttribute("required");
                horaFin.removeAttribute("required");
                // Borrar datos de horas
                horaInicio.value = "";
                horaFin.value = "";
            }
            break;
        default:
            // Secciones de horas
            horaInicio.removeAttribute("required");
            horaFin.removeAttribute("required");
            // Borrar datos de horas
            horaInicio.value = "";
            horaFin.value = "";
            //Secciones de fechas
            fechaInicio.removeAttribute("required");
            fechaFin.removeAttribute("required");
            // Borrar datos de fechas
            fechaInicio.value = "";
            fechaFin.value = "";
            alert("Seleccione un motivo para continuar");
                event.preventDefault();
                return;
            break;
        }
    }
    function validarHoras() {
        var horaInicio = document.getElementById("hora_inicio").value;
        var horaFin = document.getElementById("hora_fin").value;

        if (horaInicio !== "" && horaFin !== "") {
            var difHoras = calcularDiferenciaHoras(horaInicio, horaFin);
            if (difHoras % 60 !== 0) {
                alert("La diferencia entre la hora de inicio y la hora de fin debe ser una hora exacta.");
                event.preventDefault();
                return false;
            }
        }

        return true;
    }

    function calcularDiferenciaHoras(horaInicio, horaFin) {
        var inicio = new Date("1970-01-01T" + horaInicio);
        var fin = new Date("1970-01-01T" + horaFin);
        var difMilisegundos = fin - inicio;
        var difMinutos = Math.ceil(difMilisegundos / (1000 * 60));
        return difMinutos;
    }

    // Asignar la función de validación de horas al evento de envío del formulario
    document.getElementById("solicitud_permiso_form").addEventListener("submit", validarHoras);

    // Asignar la función de actualización a los eventos de cambio
    document.getElementById("motivo").addEventListener("change", actualizarCampos);
    document.getElementById("fecha_inicio").addEventListener("change", actualizarCampos);
    document.getElementById("hora_inicio").addEventListener("change", actualizarCampos);
    document.getElementById("fecha_fin").addEventListener("change", actualizarCampos);
    document.getElementById("hora_fin").addEventListener("change", actualizarCampos);

    document.addEventListener("DOMContentLoaded", function () {
        var formulario = document.getElementById("solicitud_permiso_form");

        formulario.addEventListener("submit", function (event) {
            var fechaInicio = new Date(formulario.fecha_inicio.value);
            var fechaFin = new Date(formulario.fecha_fin.value);
            var horaInicio = new Date("1970-01-01T" + formulario.hora_inicio.value);
            var horaFin = new Date("1970-01-01T" + formulario.hora_fin.value);

            // Validar que la fecha de inicio sea mayor a la fecha actual
            if (fechaInicio <= new Date()) {
                alert("La fecha de inicio debe ser superior a la fecha actual.");
                event.preventDefault();
                return;
            }

            // Validar que la fecha de inicio sea menor a la fecha fin
            if (fechaInicio >= fechaFin) {
                alert("La fecha final del permiso debe ser mayor a la fecha de incio del mismo");
                event.preventDefault();
                return;
            }

            // Validar que la diferencia entre la fecha de inicio y la fecha de fin sea mayor a dos días
            var diferenciaDias = Math.ceil((fechaFin - fechaInicio) / (1000 * 60 * 60 * 24));
            if (diferenciaDias <= 1) {
                alert("La diferencia entre la fecha de inicio y la fecha de fin debe ser mayor a un día.");
                event.preventDefault();
                return;
            }

            // Validar que la hora de inicio sea mayor a la hora fin
            if (horaInicio >= horaFin) {
                alert("La hora final del permiso debe ser mayor a la hora de inicio del mismo");
                event.preventDefault();
                return;
            }
        });
    });
</script>
<?php include_once("../plantilla/footer.php")?>

    <!-- <h1>Carpeta Usuarios o Funcionarios

    <div class="container-fluid">
    <div class="col-12 px-5">
        <div class="d-flex">
            <p class="m-2 col-10 pl-0 font-weight-bold text-primary">Estadisticas de Trabajo</p>
        </div>

        <div class="canvas shadow m-2">
            <div class=" canvas ">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 px-5">
        <div class="d-flex">
            <p class="m-2 col-10 pl-0 font-weight-bold text-primary">Estadisticas de Vacaciones</p>
        </div>
        <div class="canvas shadow m-2 ">
            <div class="canvas">
                <canvas id="Newchart"></canvas>
            </div>
        </div>
    </div>
</div> -->

<!-- <script>
var labels = [];

const data = {
    labels: labels,
    datasets: [
        {
            label: 'Trabajo',
            data: [],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192)',
            fill: false,
        },
    ],
};

const config = {
    type: 'bar',
    data: data,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Estadísticas de Trabajo',
            },
        },
        interaction: {
            intersect: false,
        },
        scales: {
            x: {
                display: true,
                title: {
                    display: true,
                    text: 'X trabajo',
                },
            },
            y: {
                display: true,
                title: {
                    display: true,
                    text: 'y trabajo',
                },
                suggestedMin: 0,
                suggestedMax: 100,
            },
        },
    },
};

const Newdata = {
    labels: [],
    datasets: [
        {
            label: 'Vacaciones',
            data: [],
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132)',
            fill: false,
        },
    ],
};

const Newconfig = {
    type: 'bar',
    data: Newdata,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Estadísticas de Vacaciones',
            },
        },
        interaction: {
            intersect: false,
        },
        scales: {
            x: {
                display: true,
                title: {
                    display: true,
                    text: 'X Vacaciones',
                },
            },
            y: {
                display: true,
                title: {
                    display: true,
                    text: 'Y Vacaciones',
                },
                suggestedMin: 0,
                suggestedMax: 10,
            },
        },
    },
};

var ctx = document.getElementById("myChart").getContext("2d");
var myChart = new Chart(ctx, config);

var newChart = document.getElementById("Newchart").getContext("2d");
var NewChart = new Chart(newChart, Newconfig);

</script> -->


<?php include_once("../plantilla/footer.php")?>
