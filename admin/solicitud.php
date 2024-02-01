
<?php
include_once "../redirection.php";
include_once "../flash_messages.php";
session_start();

if (!isset($_SESSION['id_usuarios'])) {
    redirect(RUTA_ABSOLUTA . 'logout');
}
$id = $_SESSION['id_usuarios'];
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
include_once  "../funciones.php";
$vista = cons_table($pdo);

?>
                    <!-- Page Heading -->
                    <h5 class="mb-3">Datos de los Funcionario que han solicitado Un permiso</h5>

                    <!-- <h1 class="h3 mb-3 text-gray-800">Debe existir una parte que carge par realizar solicitudes por el id de la sesion del funcionario</h1>
                    <p>donde debe verifica si su solicitud fue aprobada o no Va a cargar todas las solicitudes realizadas</p> -->

                    <p class="text-gray-900" id="mensaje_dias_vacaciones">El usuario  tiene 0 días de vacaciones Y se han ocupado en total 0 días de permiso.</p>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header d-flex py-3">
                            <p class="m-2 col-10 pl-0 font-weight-bold text-primary">Vista de las tablas con los datos para una solicitud
                            </p>
                            <select id="select_user" class="w-100">
                                <option disabled selected>Seleccione el Usuario</option>
                                <?php
                                        $itero = cedulasConSoli($pdo);
                                        if (!empty($itero)):

                                        foreach ($itero as $key => $posicion):
                                        $id_iterado = $posicion ["id_usuarios"];
                                        $cedula_iterada = $posicion ["cedula"];
                                        $nombres_iterado = $posicion ["nombres"];
                                        $apellidos_iterado = $posicion ["apellidos"];
                                    ?>
                                    <option value="<?php echo $id_iterado;?>"><?php echo $nombres_iterado . " ".$apellidos_iterado ;?></option>

                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>No hay datos disponibles</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive crud-table">
                                <table class="table table-bordered" id="tabla_Funcionarios">
                                    <thead>
                                        <tr>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Cedula</th>
                                            <th>Dias Solicitadas</th>
                                            <th>Horas Solicitadas</th>
                                            <th>Con Cargo a vacaciones</th>
                                            <th>Solicitud</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>


                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

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
                    <form action="../solicitarPermiso" method="post" id="solicitud_permiso_form">
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
                                <select id="motivo" class="form-select" name="motivo" required>
                                    <option value="" disabled selected>Motivo del Permiso</option>
                                    <option value="LICENCIA_POR_CALAMIDAD_DOMESTICA">Licencia por calamidad domestica</option>
                                    <option value="LICENCIA_POR_ENFERMEDAD">Licencia por enfermedad</option>
                                    <option value="LICENCIA_POR_MATERNIDAD">Licencia por maternidad</option>
                                    <option value="LICENCIA_POR_MATRIMONIO_O_UNION_DE_ECHO">Licencia por matrimonio o union de echo</option>
                                    <option value="LICENCIA_POR_PATERNIDAD">Licencia por paternidad</option>
                                    <option value="PERMISO_PARA_ESTUDIOS_REGULARES">Permiso para estudios regulares</option>
                                    <option value="PERMISO_DE_DIAS_CON_CARGO_A_VACACIONES">Permisos de dias con cargo a vacaciones</option>
                                    <option value="PERMISO_POR_ASUNTOS_OFICIALES">Permiso por asuntos oficales</option>
                                    <option value="PERMISO_PARA_ATENCION_MEDICA">Permiso para atencion medica</option>
                                    <option value="OTROS">Otros</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col" id="div_fecha_inicio" class="fecha-div">
                                <input class="form-control" type="text" name="fecha_inicio" placeholder="Fecha de Inicio" id="fecha_inicio" onfocus="(this.type='date')" onblur="(this.type='text')" required/>
                            </div>
                            <div class="col" id="div_fecha_fin" class="fecha-div">
                                <input class="form-control" type="text" name="fecha_fin" placeholder="Fecha Fin" id="fecha_fin" onfocus="(this.type='date')" onblur="(this.type='text')" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col" id="div_hora_inicio" class="hora-div">
                                <input class="form-control" type="text" name="hora_inicio" placeholder="Hora de Inicio" id="hora_inicio" onfocus="(this.type='time')" onblur="(this.type='text')" required/>
                            </div>
                            <div class="col" id="div_hora_fin" class="hora-div">
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

<script>
    $(".cerrarModal").click(function(){
        $("#registrar_vacaciones").modal('hide');
    });

    $(document).ready(function () {
        $('#select_user').select2();

        // Evento change para el select
        $('#select_user').change(function () {
            var selectedUserId = $(this).val();

            // Llamada a la función que realiza la solicitud Ajax
            actualizarTabla(selectedUserId);
        });

        // Función para realizar la solicitud Ajax y actualizar la tabla
        function actualizarTabla(selectedUserId) {
            $.ajax({
                type: 'POST',
                url: '../resta',
                data: { id_usuario: selectedUserId },
                success: function (data) {
                    // Actualizar la tabla con los nuevos datos
                    $('#tabla_Funcionarios tbody').html(data);

                    // Obtener el mensaje de días de vacaciones y permisos
                    var mensaje = obtenerMensajeDiasVacaciones(selectedUserId);

                    // Asignar el mensaje al contenido del elemento <p>
                    $('#mensaje_dias_vacaciones').text(mensaje);

                    // Estilizar los botones usando clases de Bootstrap
                    $('#tabla_Funcionarios button.btn-danger').addClass('btn  btn-danger');
                    $('#tabla_Funcionarios button.btn-success').addClass('btn btn-success');
                    $('#tabla_Funcionarios button.btn-primary').addClass('btn btn-primary');
                },
                error: function (error) {
                    console.log('Error en la solicitud Ajax: ', error);
                }
            });
        }
        function obtenerMensajeDiasVacaciones(selectedUserId) {
            var mensaje = '';

            $.ajax({
                type: 'POST',
                url: '../resta2', // Reemplaza con la ruta correcta a tu script PHP
                data: { id_usuario: selectedUserId },
                async: false, // Establece async a false para que la solicitud sea síncrona
                success: function (data) {
                    mensaje = data;
                },
                error: function (error) {
                    console.log('Error en la solicitud Ajax para obtener mensaje: ', error);
                }
            });

            return mensaje;
        }
    });

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
            //Secciones de horas
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
