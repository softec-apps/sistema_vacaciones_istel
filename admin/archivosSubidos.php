
<?php
include_once "../redirection.php";
include_once "../flash_messages.php";
session_start();

if (!isset($_SESSION['id_usuarios'])) {
    redirect(RUTA_ABSOLUTA . 'logout');
}
$cedula = $_SESSION['cedula'];
$nombre = $_SESSION['nombres'];
$rol = $_SESSION['rol'];

if ($rol !== ROL_ADMIN) {
   redirect(RUTA_ABSOLUTA . "logout");
}
$message = '';
$type = '';
$flash_message = display_flash_message();

if (isset($flash_message)) {
    $message = $flash_message['message'];
    $type = $flash_message['type'];
}

$titulo = "Archivos Funcionario";
include_once("../plantilla/header.php")
?>


<div class="container-fluid mt-4">
<?php
include_once  "../conexion.php";
include_once  "../resta_solicitud.php";
include_once  "../funciones.php";
$vista = vista1($pdo);

?>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Todos los usuarios que tienen solicitudes hechas</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">En esta sección se puede dirigir a los archivos relacionados con cada permiso del usuario </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered crud-table" id="tablaArchivosSubidos">
                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Modalidad</th>
                            <th class="exclude">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (empty($vista)) {
                            echo "";

                        }else {
                            foreach ($vista as $key => $valor){
                                $id_usuarios  = $valor ["id_usuarios"];
                                $nombres = $valor ["nombres"];
                                $cedula = $valor ["cedula"];
                                $apellidos = $valor ["apellidos"];
                                $tiempoTrabajo = $valor ["tiempo_trabajo"];
                                if ($tiempoTrabajo == 8) {
                                    $tiempoTrabajo = "Tiempo completo";
                                }elseif ($tiempoTrabajo == 4) {
                                    $tiempoTrabajo = "Medio tiempo";
                                }else {
                                    $tiempoTrabajo = "";
                                }

                        ?>
                        <tr>

                            <td>
                            <?= $cedula ?>
                            </td>

                            <td>
                                <?= $nombres ?>
                            </td>

                            <td>
                                <?= $apellidos ?>
                            </td>


                            <td>
                            <?= $tiempoTrabajo ?>
                            </td>

                            <td class="d-flex">
                                <form action="<?= RUTA_ABSOLUTA  ?>admin/consulta_trabajo" method="post">
                                    <input type="hidden" name="id_usuario" id="usuario_id" value="<?= $id_usuarios ?>">
                                    <button class="btn btn-primary m-1" type="submit" title="Todos los archivos de los permisos"><i class="fa-regular fa-folder-open"></i></button>
                                </form>
                                <form action="<?= RUTA_ABSOLUTA  ?>admin/archivosUser" method="post">
                                    <input type="hidden" name="id_usuario" id="usuario_id" value="<?= $id_usuarios ?>">
                                    <button class="btn btn-primary m-1" type="submit" title="Archivos del usuario"><i class="fa-solid fa-id-badge"></i></button>
                                </form>
                                <form action="<?= RUTA_ABSOLUTA  ?>admin/archivosAprobados" method="post">
                                    <input type="hidden" name="id_usuario" id="usuario_id" value="<?= $id_usuarios ?>">
                                    <button class="btn btn-primary m-1" type="submit" title="Archivos del jefe"><i class="fa-solid fa-user-tie"></i></button>
                                </form>
                                <form action="<?= RUTA_ABSOLUTA  ?>admin/archivosRegistrados" method="post" title="Archivos registrados">
                                    <input type="hidden" name="id_usuario" id="usuario_id" value="<?= $id_usuarios ?>">
                                    <button class="btn btn-primary m-1" type="submit" ><i class="fa-solid fa-user"></i></button>
                                </form>
                            </td>

                        </tr>
                        <?php
                            };
                        };
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
                <form id="eliminarForm" action="<?php echo RUTA_ABSOLUTA ?>admin/eliminar" method="post">
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

<!-- modal del archivo escaneado -->
<div class="modal fade" id="subirEscaneado" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdminLabel">Subir archivo escaneado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <p>Por favor para que el permiso se pueda rechazar o aceptar se debe subir el archivo firmado</p> -->
                <form id="subirForm" action="<?php echo RUTA_ABSOLUTA ?>archivos" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_permiso" id="id_permisoSubir" value="">
                    <label >Archivo firmado por el solicitante</label>
                    <input class="form-control" type="file" name="archivo" id="archivo" required>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="subirForm" class="btn btn-primary">Subir archivo</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal aceptar Solicitud -->
<div class="modal fade" id="aceptarSolicitud" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdminLabel">¿Está seguro de que desea aprobar esta solicitud ?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <p>¿Está seguro de que desea Aceptar esta solicitud ?</p> -->
                <form id="AceptarS" action="<?php echo RUTA_ABSOLUTA ?>procesar" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_aprueba" id="id_aprueba" value ="" />
                    <input class="form-control" type="hidden" name="aprobar" value ="1" />
                    <div class="form-floating mb-3">
                        <div>
                            <select class="selectSoli" name="user" required style="width:100%;">
                                <option value="" disabled selected>Seleccione al usuario que aprueba la solicitud</option>
                                <?php
                                    $iteroSinAdmin = sinAdmin($pdo);

                                    foreach ($iteroSinAdmin as $key => $posicionSin):
                                    $id_iterado = $posicionSin ["id_usuarios"];
                                    $cedula_iterada = $posicionSin ["cedula"];
                                    $nombres_iterado = $posicionSin ["nombres"];
                                    $apellidos_iterado = $posicionSin ["apellidos"];
                                    $nombreCompleto = $nombres_iterado . " " . $apellidos_iterado;
                                ?>
                                <option value="<?php echo $nombreCompleto;?>"><?php echo $nombreCompleto;?></option>

                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <div>
                            <label>Archivo firmado por el jefe supervisor</label>
                            <input class="form-control" type="file" name="archivo" id="archivo" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="AceptarS" class="btn btn-primary">Aprobar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal para cancelar una solicitud -->
<div class="modal fade" id="cancelarSolicitud" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdminLabel">Cancelar el permiso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea cancelar este permiso ?</p>
                <form id="cancelarS" action="<?php echo RUTA_ABSOLUTA ?>procesar" method="POST">
                    <input type="hidden" name="id_cancelar" id="id_cancelar" value ="<?= $id_permiso ?>" />
                    <input class="form-control" type="hidden" name="cancelar" value ="0" />
                    <input class="form-control" type="hidden" name="user" value="" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="cancelarS" class="btn btn-primary">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('.select_soli').select2({
        dropdownParent: $('#registrar_vacaciones .modal-body')
    });
    $('.selectSoli').select2({
        dropdownParent: $('#aceptarSolicitud .modal-body')
    });

    $(".cerrarModal").click(function(){
        $("#registrar_vacaciones").modal('hide');
    });aceptar
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
    function aceptar(button) {
        var userId = button.getAttribute('data-id');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('id_aprueba').value = userId;
    }
    function cancelar(button) {
        var userId = button.getAttribute('data-id');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('id_cancelar').value = userId;
    }
    function updateHiddenField(selectElement) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var nombreUsuario = selectedOption.text;
        document.getElementById('nombre_usuario').value = nombreUsuario;
    }
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
