
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

$titulo = "Solicitud";
include_once("../plantilla/header.php")
?>


<div class="container-fluid mt-5">
<?php
include_once  "../conexion.php";
include_once  "../resta_solicitud.php";
include_once  "../funciones.php";
$vista = obtenerUsuariosConPermios($pdo);

?>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Vista general para ver todos los funcionarios que han solicitado un permiso</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Aqui se puede generar una solicitud para el usuario </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered crud-table" id="tabla_vacaciones_funcionarios">
                    <thead>
                        <tr>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Cedula</th>
                            <th>D.T</th>
                            <th>H.DT.A</th>
                            <th class="exclude">P.A</th>
                            <th class="exclude">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (empty($vista)) {
                            echo "";

                        }else {
                            foreach ($vista as $key => $valor){
                                $id_usuarios  = $valor ["id_usuario"];
                                $id_permiso  = $valor ["id_permisos"];
                                $nombres = $valor ["nombre"];
                                $cedula = $valor ["cedula"];
                                $apellidos = $valor ["apellido"];
                                $diasTrabajados = $valor ["dias_trabajados"];
                                $permiso_aceptado = $valor ["permisoUsuario"];

                                $horasDePermisoSolicitadas = $valor ["horas_permiso"];
                                $fechaIngreso = $valor ["fecha_ingreso"];
                                $tiempoTrabajo = $valor ["tiempo_trabajo"];



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
                        ?>
                        <tr>
                            <td>
                                <?= $nombres ?>
                            </td>

                            <td>
                                <?= $apellidos ?>
                            </td>

                            <td>
                            <?= $cedula ?>
                            </td>

                            <td>
                            <?= $diasTrabajados ?>
                            </td>

                            <td>
                            <?= $dias_totales ?>
                            </td>

                            <td>
                            <?php if ($permiso_aceptado == 0): ?>
                                <button class="btn btn-warning" title="Solicitud en proceso" data-toggle="modal"
                                    data-target="#aceptarSolicitud" data-id="<?= $id_permiso ?>" onclick="aceptar(this)"><i class="fa-solid fa-sync fa-spin"></i></button>

                            <?php elseif ($permiso_aceptado == 1): ?>

                                <button class="btn btn-success" title="Aceptado" data-toggle="modal"
                                    data-target="#cancelarSolicitud" data-id="<?= $id_permiso ?>" onclick="cancelar(this)"><i class="fa-solid fa-check"></i></button>

                            <?php elseif ($permiso_aceptado == 2): ?>
                                <button class="btn btn-info" title="Permiso Rechazado" ><i class="fa-solid fa-xmark"></i></button>

                            <?php elseif ($permiso_aceptado == 3): ?>
                                <button class="btn btn-primary" title="Ya registrado" ><i class="fa-solid fa-check"></i></button>
                            <?php endif; ?>
                            </td>

                            <td>
                                <form action="../datos_individuales" method="POST" class="d-inline-block m-1">
                                    <input type="hidden" name="id_permisos" value=" <?= $id_permiso ?>">
                                    <button class="btn btn-info m-1" title="Ver los datos de esta solicitud">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </form>
                                <?php if ($permiso_aceptado == 1 || $permiso_aceptado == 3 ): ?>

                                <?php elseif ($permiso_aceptado == 0): ?>
                                    <button class="btn btn-danger m-1" title="Eliminar" data-toggle="modal"
                                    data-target="#Eliminar" data-id="<?= $id_permiso ?>" onclick="eliminar(this)">
                                    <i class="fas fa-trash"></i>
                                    </button>
                                <?php endif; ?>
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

<!-- Modal para solicitar permisos -->
<div class="modal fade" id="registrar_vacaciones" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdminLabel">Solicitar Permiso </h5>
                <button type="button" class="close cerrarModal" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../solicitar_permiso" method="post" id="solicitud_permiso_form">
                    <input type="hidden" name="nombre_usuario" id="nombre_usuario">

                    <div class="row mb-3">
                        <div class="col">
                            <select class="form-select" name="id_usuario" onchange="updateHiddenField(this)" required>
                                <option value="" disabled selected>Seleccione un usuario</option>
                                <?php
                                    $itero = cedulas($pdo);

                                    foreach ($itero as $key => $posicion):
                                    $id_iterado = $posicion ["id_usuarios"];
                                    $cedula_iterada = $posicion ["cedula"];
                                    $nombres_iterado = $posicion ["nombres"];
                                    $apellidos_iterado = $posicion ["apellidos"];
                                ?>
                                <option value="<?php echo $id_iterado;?>"><?php echo $nombres_iterado . " ".$apellidos_iterado ;?></option>

                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>

                        <div class="col">
                            <select class="form-select" name="provincia" required >
                                <option value="" disabled selected>Provincias</option>
                                <option value="Azuay">Azuay</option>
                                <option value="Bolívar">Bolívar</option>
                                <option value="Cañar">Cañar</option>
                                <option value="Carchi">Carchi</option>
                                <option value="Chimborazo">Chimborazo</option>
                                <option value="Cotopaxi">Cotopaxi</option>
                                <option value="El Oro">El Oro</option>
                                <option value="Esmeraldas">Esmeraldas</option>
                                <option value="Galápagos">Galápagos</option>
                                <option value="Guayas">Guayas</option>
                                <option value="Imbabura">Imbabura</option>
                                <option value="Loja">Loja</option>
                                <option value="Los Ríos">Los Ríos</option>
                                <option value="Manabí">Manabí</option>
                                <option value="Morona Santiago">Morona Santiago</option>
                                <option value="Napo">Napo</option>
                                <option value="Orellana">Orellana</option>
                                <option value="Pastaza">Pastaza</option>
                                <option value="Pichincha">Pichincha</option>
                                <option value="Santa Elena">Santa Elena</option>
                                <option value="Santo Domingo de los Tsáchilas">Santo Domingo de los Tsáchilas</option>
                                <option value="Sucumbíos">Sucumbíos</option>
                                <option value="Tungurahua">Tungurahua</option>
                                <option value="Zamora-Chinchipe">Zamora-Chinchipe</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <!-- <label>Ingrese el Regimen </label> -->
                            <input class="form-control" type="text" name="regimen" placeholder="Regimen" required/>
                        </div>
                        <div class="col">
                            <!-- <label>Ingrese la coordinacion Zonal </label> -->
                            <input class="form-control" type="text" name="coordinacion" placeholder="Coordinacion Zonal"   required/>
                        </div>
                    </div>



                    <div class="row mb-3">
                        <div class="col">
                            <!-- <label>Ingrese la Dirrecion o Unidad </label> -->
                            <input class="form-control" type="text" name="direccion" placeholder="Direccion o Unidad"  required/>
                        </div>
                        <div class="col">
                            <!-- <label>Motivo </label> -->
                            <select class="form-select" name="motivo" id="motivo" required>
                                <option value="" disabled selected>Motivo del Permiso</option>
                                <option value="LICENCIA_POR_CALAMIDAD_DOMESTICA">Licencia por calamidad domestica</option>
                                <option value="LICENCIA_POR_ENFERMEDAD">Licencia por enfermedad</option>
                                <option value="LICENCIA_POR_MATERNIDAD">Licencia por maternidad</option>
                                <option value="LICENCIA_POR_MATRIMONIO_O_UNION_DE_ECHO">Licencia por matrimonio o union de echo</option>
                                <option value="LICENCIA_POR_PATERNIDAD">Licencia por paternidad</option>
                                <option value="PERMISO_PARA_ESTUDIOS_REGULARES">Permiso pra estudios regulares</option>
                                <option value="PERMISO_DE_DIAS_CON_CARGO_A_VACACIONES">Permisos de dias con cargo a vacaciones</option>
                                <option value="PERMISO_POR_ASUNTOS_OFICIALES">Permiso por asuntos oficales</option>
                                <option value="PERMISO_PARA_ATENCION_MEDICA">Permiso para atencion medica</option>
                                <option value="OTROS">Otros</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col" id="div_fecha_inicio">
                            <!-- <label for="fecha_inicio" id="label_fecha_inicio">fecha inicio del permiso</label> -->
                            <input class="form-control" type="text" name="fecha_inicio" placeholder="Fecha de Inicio" id="fecha_inicio" onfocus="(this.type='date')" onblur="(this.type='text')" required/>
                        </div>
                        <div class="col" id="div_fecha_fin">
                            <!-- <label for="fecha_fin" id="label_fecha_fin">fecha fin del permiso</label> -->
                            <input class="form-control" type="text" name="fecha_fin" placeholder="Fecha Fin" id="fecha_fin" onfocus="(this.type='date')" onblur="(this.type='text')" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col" id="div_hora_inicio">
                            <!-- <label for="hora_inicio" id="label_hora_inicio">hora inicio del permiso</label> -->
                            <input class="form-control" type="text" name="hora_inicio" placeholder="Hora de Inicio" id="hora_inicio" onfocus="(this.type='time')" onblur="(this.type='text')" required/>
                        </div>
                        <div class="col" id="div_hora_fin">
                            <!-- <label for="hora_fin" id="label_hora_fin">hora fin del permiso</label> -->
                            <input class="form-control" placeholder="Hora Fin" type="text" name="hora_fin" placeholder="Hora Fin" id="hora_fin" onfocus="(this.type='time')" onblur="(this.type='text')"/>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <input class="form-control" placeholder="Observaciones o Justificativos"  type="text" name="observaciones"  />
                        </div>
                    </div>

                    <input class="form-control" type="hidden" name="permiso_aceptado" value="0" />

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger cerrarModal" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Solicitar Permiso</button>
                    </div>
                </form>
            </div>
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
<!-- Modal aceptar Solicitud -->
<div class="modal fade" id="aceptarSolicitud" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdminLabel">¿Está seguro de que desea Aceptar esta solicitud ?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <p>¿Está seguro de que desea Aceptar esta solicitud ?</p> -->
                <form id="AceptarS" action="<?php echo RUTA_ABSOLUTA ?>procesar" method="POST">
                    <input type="hidden" name="id_aprueba" id="id_aprueba" value ="" />
                    <input class="form-control" type="hidden" name="aprobar" value ="1" />
                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese su nombre para aprobar  </label>
                            <input class="form-control" type="text" name="user" required/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="AceptarS" class="btn btn-primary">Aprobar</button>
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
                <h5 class="modal-title" id="modalAdminLabel">Confirmar Cancelacion del Permiso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea Cancelar esta solicitud ?</p>
                <form id="cancelarS" action="<?php echo RUTA_ABSOLUTA ?>procesar" method="POST">
                    <input type="hidden" name="id_cancelar" id="id_cancelar" value ="<?= $id_permiso ?>" />
                    <input class="form-control" type="hidden" name="cancelar" value ="0" />
                    <input class="form-control" type="hidden" name="user" value="" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="cancelarS" class="btn btn-warning">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(".cerrarModal").click(function(){
        $("#registrar_vacaciones").modal('hide');
    });aceptar
    function eliminar(button) {
        var userId = button.getAttribute('data-id');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('id_permiso').value = userId;
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
            if (diferenciaDias <= 2) {
                alert("La diferencia entre la fecha de inicio y la fecha de fin debe ser mayor a dos días.");
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
