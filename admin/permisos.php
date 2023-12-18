<?php
include_once "../redirection.php";
session_start();

if (!isset($_SESSION['id_usuarios'])) {
    redirect(RUTA_ABSOLUTA . 'logout');
}
$cedula = $_SESSION['cedula'];
$nombre = $_SESSION['nombres'];
$rol = $_SESSION['rol'];

if ($rol != ROL_ADMIN) {
   redirect(RUTA_ABSOLUTA . "logout");
}

$titulo = "Permisos";
include_once("../plantilla/header.php")
?>

                <div class="container-fluid mt-5">
                <p>Debe mostrarme un mensaje de alerta si talentos humanos no ha ingresado dias de trabajo de los funcionarios</p>

                <p>Talentos humanos puede ingresar desde estea parte los dias de trabajo del funcionario o tambien lo puede acer en otra seccion para los funcionarios registrados y no solo para los que el supervisor aprueba</p>
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Permisos aceptados por el supervisor</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Permisos aceptados por el supervisor</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive crud-table">
                                <table class="table table-bordered" id="tabla_permisos">
                                    <thead>
                                        <tr>
                                            <th>Cedula</th>
                                            <th>Nombres</th>
                                            <th>Provincia</th>
                                            <th>Regimen</th>
                                            <th>Coordinacion</th>
                                            <th>Direccion o unidad</th>
                                            <th>Fecha emitida</th>
                                            <th>Tipo permiso</th>
                                            <th>Desc permiso</th>
                                            <th>Dias Solicitados</th>
                                            <th>Horas Solicitadas</th>
                                            <th>Fecha  Inicio</th>
                                            <th>Fecha  Fin</th>
                                            <th>Hora  Inicio</th>
                                            <th>Hora  Fin</th>
                                            <th>Usuario Solicita</th>
                                            <th>Usuario Aprueba</th>
                                            <th>Usuario Registra</th>
                                            <th class="exclude">Acciones</th>
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

                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>
                                            <td>
                                                <button class="btn btn-secondary" title="Registrar la solicitud"
                                                    data-toggle="modal" data-target="#Editar_datos">
                                                    <i class="fa-solid fa-pencil"></i>
                                                </button>
                                            </td>
                                        </tr>

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
                    <p>¿Está seguro de que desea eliminar este usuario?</p>
                    <form id="eliminarForm" action="eliminar_admin" method="post">
                        <input type="hidden" name="cliente_id" id="cliente_id" value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" form="eliminarForm" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </div>




    <!-- Modal Editar Pemisos-->
    <div class="modal fade" id="Editar_permisos" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdminLabel">Editar Permisos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="actualizar_permisos" method="POST">
                        <input type="hidden" name="id_administrador" id="id_administrador" value="">

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="permisos[]" value="Administracion"
                                id="permiso_administracion">
                            <label class="form-check-label" for="permiso_administracion">Administración</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="permisos[]" value="Bolsa de empleo"
                                id="permiso_bolsa">
                            <label class="form-check-label" for="permiso_bolsa">Bolsa de Empleo</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="permisos[]"
                                value="Seguimiento graduados" id="permiso_seguimiento">
                            <label class="form-check-label" for="permiso_seguimiento">Seguimiento Graduados</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="permisos[]"
                                value="Eventos y Encuestas" id="eventos_encuestas">
                            <label class="form-check-label">Eventos y Encuestas</label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Actualizar los Permisos</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Editar datos del usuario -->
    <div class="modal fade" id="Editar_datos" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdminLabel">Registrar Solicitud del Funcionario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <p>Estás a punto de registrar una solicitud de permiso del usuario con los siguientes datos:</p>
                        <div class="form-floating mb-3 mt-3">
                            <p>Cedula : 00000000000  </p>
                        </div>

                        <div class="form-floating mb-3 mt-3">
                            <p>Provincia : X </p>
                        </div>

                        <div class="form-floating mb-3 mt-3">
                            <p>Regimen : X</p>
                        </div>

                        <div class="form-floating mb-3 mt-3">
                            <p>Dirrecion o Unidad : X</p>
                        </div>

                        <div class="form-floating mb-3 mt-3">
                            <p>Fecha inicio del permisos : X</p>
                        </div>

                        <div class="form-floating mb-3 mt-3">
                            <p>Fecha fin del permisos : X</p>
                        </div>

                        <div class="form-floating mb-3 mt-3">
                            <p>Desde (hh:mm) : xx:xx</p>
                        </div>

                        <div class="form-floating mb-3 mt-3">
                            <p>Hasta (hh:mm) : xx:xx</p>
                        </div>

                        <div class="form-floating mb-3 mt-3">
                            <p>Con el motivo : x</p>
                        </div>

                        <div class="form-floating mb-3 mt-3">
                            <p>Con las observaciones o justificativos del permisos(opcional) : XXXXXXX</p>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger cerrarModal" data-dismiss="modal">No registrar</button>
                            <button type="submit" class="btn btn-success">Registrar Solicitud de permiso</button>
                        </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal para registrar nuevos permisos -->
    <div class="modal fade" id="registrar_permisos" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdminLabel">Registrar solicitudes de permisos</h5>
                    <button type="button" class="close cerrarModal" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Selecciona "Registrar" si deseeas registrar todas las solicitudes de todos los funcionarios</p>
                    <form id="eliminarForm" action="eliminar_admin" method="post">
                        <input type="hidden" name="cliente_id" id="cliente_id" value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cerrarModal" data-dismiss="modal">Cancelar</button>
                    <button type="submit" form="eliminarForm" class="btn btn-primary">Registrar</button>
                </div>
            </div>
        </div>
    </div>

<script>
    $(".cerrarModal").click(function(){
  $("#registrar_permisos").modal('hide')
});
</script>
<?php include_once("../plantilla/footer.php")?>
