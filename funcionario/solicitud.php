
<?php
include_once "../redirection.php";
session_start();

if (!isset($_SESSION['id_usuarios'])) {
    redirect(RUTA_ABSOLUTA . 'logout');
}
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

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Estadisticas de los funcionarios</h1>

                    <p>Solicitud de permiso de los funcionarios mas estadisticas de trabajo y vaciones</p>
                    <p>Verifica si su solicitud fue aprobada o no</p>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Estadisticas de los funcionarios</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive crud-table">
                                <table class="table table-bordered" id="tabla_vacaciones_funcionarios">
                                    <thead>
                                        <tr>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Dias laborados</th>
                                            <th>Dias limite vaciones</th>
                                            <th>Dias totales vacaciones</th>
                                            <th>Dias limite acumulados</th>
                                            <th>Dias totales acumulados</th>
                                            <th>Solicitud de Permiso</th>
                                            <th>Verificar solicitud</th>
                                            <th>Dias solicitados</th>
                                            <th>Verificar solicitud</th>
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
                                            <button class="btn btn-primary" data-toggle="modal"
                                            data-target="#registrar_vacaciones"> Hacer solicitud</button>
                                            </td>

                                            <td>
                                            <button class="btn btn-primary disabled" data-toggle="modal"
                                            data-target="#registrar_vacaciones"> sin solicitar</button>
                                            </td>

                                            <td>
                                            <button class="btn btn-primary disabled" data-toggle="modal"
                                            data-target="#registrar_vacaciones"> sin solicitar</button>
                                            </td>

                                            <td>
                                                <button class="btn btn-warning text-dark disabled" > no hay solicitud realizada</button>
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
                                <input class="form-control" name="user" type="text" required />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese el nuevo Email del Usuario</label>
                                <input class="form-control" type="email" name="email" required />
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
                    <form action="editar_datos" method="post">
                        <input type="hidden" name="cliente_id" value="">
                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese la cedula del funcionario que solicita el permiso </label>
                                <input class="form-control" name="user" type="text" required />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese la provincia </label>
                                <input class="form-control" type="email" name="email" required />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese el Regimen </label>
                                <input class="form-control" type="email" name="email" required />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese la Dirrecion o Unidad </label>
                                <input class="form-control" type="email" name="email" required />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese la fecha inicio del permisos</label>
                                <input class="form-control" type="email" name="email" required />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese la fecha fin del permisos</label>
                                <input class="form-control" type="email" name="email" required />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese la hora inicio del permisos(opcional)</label>
                                <input class="form-control" type="email" name="email" required />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese la hora fin del permisos(opcional)</label>
                                <input class="form-control" type="email" name="email" required />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Seleccione el motivo del permisos</label>
                                <input class="form-control" type="email" name="email" required />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese el tipo del permisos</label>
                                <input class="form-control" type="email" name="email" required />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese observaciones o justificativos del permisos(opcional)</label>
                                <input class="form-control" type="email" name="email" required />
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
  $("#registrar_vacaciones").modal('hide')
});
</script>
<?php include_once("../plantilla/footer.php")?>
