
<?php
include_once "../redirection.php";
session_start();

if (!isset($_SESSION['id_usuarios'])) {
    redirect(RUTA_ABSOLUTA . 'logout');
}
$id = $_SESSION['id_usuarios'];
$cedula = $_SESSION['cedula'];
$nombre = $_SESSION['nombres'];
$rol = $_SESSION['rol'];

if (($rol == ROL_JEFE)||($rol == ROL_TALENTO_HUMANO)) {
   redirect(RUTA_ABSOLUTA . "logout");
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
                    <h5 class="mb-2">Datos de los Funcionario que han solicitado Un permiso</h5>

                    <!-- <h1 class="h3 mb-2 text-gray-800">Debe existir una parte que carge par realizar solicitudes por el id de la sesion del funcionario</h1>
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




    <!-- Modal Editar datos del usuario -->
    <!-- <div class="modal fade" id="Editar_datos" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdminLabel">Editar datos del usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="editar_datos" method="post">
                        <input type="hidden" name="cliente_id" value="">
                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese el nuevo nombre de usuario</label>
                                <input class="form-control" name="user" type="text"  />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese el nuevo Email del Usuario</label>
                                <input class="form-control" type="email" name="email"  />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Actualizar Informacion</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> -->



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
                    <form action="../solicitar_permiso" method="post">
                        <input type="hidden" name="nombre_usuario" id="nombre_usuario">
                        <div class="form-floating mb-3">
                            <div>
                                <label>Seleccione la cedula del funcionario que solicita el permiso</label>
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
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese la provincia </label>
                                <input class="form-control" type="text" name="provincia"  />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese el Regimen </label>
                                <input class="form-control" type="text" name="regimen"  />
                            </div>
                        </div>


                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese la coordinacion Zonal </label>
                                <input class="form-control" type="text" name="coordinacion"  />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese la Dirrecion o Unidad </label>
                                <input class="form-control" type="text" name="direccion"  />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese la fecha inicio del permisos</label>
                                <input class="form-control" type="date" name="fecha_inicio"  />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese la fecha fin del permisos</label>
                                <input class="form-control" type="date" name="fecha_fin"  />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese la hora inicio del permisos(opcional)</label>
                                <input class="form-control" type="time" name="hora_inicio"  />
                            </div>
                        </div>


                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese la hora fin del permisos(opcional)</label>
                                <input class="form-control" type="time" name="hora_fin"  />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Seleccione el motivo del permisos</label>
                                <select class="form-control" name="motivo">
                                    <option value="LICENCIA_POR_CALAMIDAD_DOMESTICA">Licencia por calamidad domestica</option>
                                    <option value="LICENCIA_POR_ENFERMEDAD">Licencia por enfermedad</option>
                                    <option value="LICENCIA_POR_MATERNIDAD">Licencia por maternidad</option>
                                    <option value="LICENCIA_POR_MATRIMONIO_O_UNION_DE_ECHO">Licencia por matrimonio o union de echo</option>
                                    <option value="LICENCIA_POR_PATERNIDAD">Licencia por paternidad</option>
                                    <option value="PERMISO_PARA_ESTUDIOS_REGULARES">Permiso pra estudios regulares</option>
                                    <option value="PERMISO_DE_DIAS_CON_CARGO_A_VACACIONES">Permisos de dias con cargo a vacaciones</option>
                                    <option value="PERMISO_POR_ASUNTOS_OFICIALES">Permiso por asuntos oficales</option>
                                    <option value="PERMISO_PARA_ATENCION_MEDICA">Permiso para atencion medica</option>
                                    <option value="OTROS">otros</option>
                                </select>
                            </div>
                        </div>

                        <input class="form-control" type="hidden" name="permiso_aceptado" value="0" />

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese observaciones o justificativos del permisos(opcional)</label>
                                <input class="form-control" type="text" name="observaciones"  />
                            </div>
                        </div>

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

</script>
<?php include_once("../plantilla/footer.php")?>
