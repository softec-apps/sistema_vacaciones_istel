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

$titulo = "Logs Solicitudes";
include_once("../plantilla/header.php")
?>

                <div class="container-fluid mt-5">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Logs de solicitudes de los Funcionarios</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Logs de solicitudes de los Funcionarios</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive crud-table">
                                <table class="table table-bordered" id="tabla_admininstradores">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Cedula</th>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Email</th>
                                            <th>Usuario</th>
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
                                                <button class="btn btn-primary" title="Editar data del usuario"
                                                    data-toggle="modal" data-target="#Editar_datos">
                                                    <i class="fa-solid fa-pencil"></i>
                                                </button>

                                                <button class="btn btn-danger" title="Eliminar" data-toggle="modal"
                                                    data-target="#Eliminar">
                                                    <i class="fas fa-trash"></i>
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

    <!-- Modal Editar datos del usuario -->
    <div class="modal fade" id="Editar_datos" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
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
    </div>



    <!-- Modal para registrar administradores -->
    <div class="modal fade" id="registrar_administradores" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdminLabel">Editar datos del usuario</h5>
                    <button type="button" class="close cerrarModal" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="editar_datos" method="post">
                        <input type="hidden" name="cliente_id" value="">
                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese el nombre del nuevo usuario</label>
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
                            <button type="button" class="btn btn-secondary cerrarModal" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Actualizar Informacion</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    $(".cerrarModal").click(function(){
  $("#registrar_administradores").modal('hide')
});
</script>
<?php include_once("../plantilla/footer.php")?>
