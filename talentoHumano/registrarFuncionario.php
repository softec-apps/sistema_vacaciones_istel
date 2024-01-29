<?php
include_once "../redirection.php";
session_start();

if (!isset($_SESSION['id_usuarios'])) {
   redirect(RUTA_ABSOLUTA . 'logout');
}
$cedula = $_SESSION['cedula'];
$nombre = $_SESSION['nombres'];
$rol = $_SESSION['rol'];

if ($rol != ROL_TALENTO_HUMANO) {
   redirect(RUTA_ABSOLUTA . "logout");
}

$titulo = "Registrar Funcionarios";
include_once("../plantilla/header.php")
?>

    <div class="container-fluid mt-5">

<!-- Page Heading -->
<!-- <h1 class="h3 mb-2 text-gray-800">Registrar Funcionarios con el dia que inicio a trabajar</h1> -->
<?php

include_once "../funciones.php";
include_once "../conexion.php";

$resultados = mostrarUsuarios($pdo);
?>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Registrar Funcionarios con el dia que inicio a trabajar</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered crud-table" id="tabla_admininstradores">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Cedula</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Usuario</th>
                        <th>Fecha Ingreso</th>
                        <th class="exclude">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (empty($resultados)) {
                        echo "<script>alert('no ha datos registrados')</script>";
                    }else {
                        foreach ($resultados as $key => $valor){
                            $id_usuarios = $valor ["id_usuarios"];
                            $cedula_usuarios = $valor['cedula'];
                            $nombres_usuarios = $valor['nombres'];
                            $apellidos_usuarios = $valor['apellidos'];
                            $email_usuarios = $valor['email'];
                            $rol_usuarios = $valor['rol'];
                            $fecha_ingreso = $valor['fecha_ingreso'];
                            $tiempo_trabajo = $valor['tiempo_trabajo'];

                            if ($rol_usuarios == 'Funcionario') {

                                $funcionariosEncontrados = true;
                    ?>
                    <tr>
                        <td>
                            <?= $id_usuarios ?>
                        </td>

                        <td>
                            <?= $cedula_usuarios ?>
                        </td>

                        <td>
                            <?= $nombres_usuarios ?>
                        </td>

                        <td>
                            <?= $apellidos_usuarios ?>
                        </td>

                        <td>
                            <?= $email_usuarios ?>
                        </td>

                        <td>
                            <?= $rol_usuarios ?>
                        </td>

                        <td>
                            <?= $fecha_ingreso ?>
                        </td>
                        <td>
                            <button class="btn btn-primary m-1" title="Editar datos del usuario"
                                data-toggle="modal" data-target="#Editar_datos" data-id="<?= $id_usuarios ?>" data-cedula="<?= $cedula_usuarios?>" data-name="<?= $nombres_usuarios ?>" data-lastname="<?= $apellidos_usuarios ?>" data-email="<?= $email_usuarios ?>"
                                data-fecha_ingreso="<?= $fecha_ingreso ?>" data-tiempo_trabajo="<?= $tiempo_trabajo ?>" onclick="cargarDatos(this)">
                                <i class="fa-solid fa-pencil"></i>
                            </button>

                            <button class="btn btn-danger" title="Eliminar" data-toggle="modal"
                                data-target="#Eliminar" data-id="<?= $id_usuarios ?>" onclick="eliminar(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                            <!-- <form action="<?php
                            // echo RUTA_ABSOLUTA
                             ?>calcular" method="post">
                                <input type="hidden" name="id_usuario" value="<?php
                                //  echo $id_usuarios;
                                 ?>">
                                <button class="btn btn-info m-1" title="Ver dias de trabajo">
                                <i class="bi bi-eyeglasses"></i>
                                </button>
                            </form> -->
                        </td>
                    </tr>

                    <?php
                            }
                        };
                        if (!isset($funcionariosEncontrados)) {
                            echo "No existen datos de funcionarios";
                        }
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
                <p>¿Está seguro de que desea eliminar este usuario?</p>
                <form id="eliminarForm" action="<?php echo RUTA_ABSOLUTA ?>admin/eliminar_users" method="post">
                    <input type="text" name="cliente_id" id="cliente_id" value="">
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
                <form action="<?php echo RUTA_ABSOLUTA ?>admin/actualizar_users" method="post">
                    <input type="hidden" name="cliente_id" value="">
                    <input type="hidden" name="roles" value="Funcionario">

                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese la cedula del nuevo usuario</label>
                            <input class="form-control" name="cedula" type="text"/>
                        </div>
                    </div>


                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese los nombre del nuevo usuario</label>
                            <input class="form-control" name="nombres" type="text"/>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese los apellidos del nuevo usuario</label>
                            <input class="form-control" name="apellidos" type="text"/>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese el Email del Usuario</label>
                            <input class="form-control" type="email" name="email_A"/>
                        </div>
                    </div>

                    <div class="form-floating mb-3" id="optionTiempo">
                        <div>
                            <label>Seleccione el Tiempo de Trabajo del Funcionario</label>
                            <select class="form-control" name="tiempo_trabajo">
                                <option value="8">Tiempo Completo</option>
                                <option value="4">Medio Tiempo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-floating mb-3" id="opcionesFuncionario">
                        <div>
                            <label>Fecha Ingreso</label>
                            <input class="form-control" type="date" name="fecha_ingreso" required/>
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
                <h5 class="modal-title" id="modalAdminLabel">Registrar Nuevo Funcionario</h5>
                <button type="button" class="close cerrarModal" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo RUTA_ABSOLUTA ?>admin/registrar_users" method="post">
                    <input type="hidden" name="cliente_id" value="">
                    <input type="hidden" name="roles1" value="Funcionario">
                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese la cedula del nuevo Funcionario</label>
                            <input class="form-control" name="cedula" type="text" required />
                        </div>
                    </div>


                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese los nombre del nuevo Funcionario</label>
                            <input class="form-control" name="nombres" type="text" required />
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese los apellidos del nuevo Funcionario</label>
                            <input class="form-control" name="apellidos" type="text" required />
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese un usuario para el Funcionario</label>
                            <input class="form-control" name="user_A" type="text" required />
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese la contraseña para el Funcionario</label>
                            <input class="form-control" type="text" name="password_A" required />
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese el Email del Funcionario</label>
                            <input class="form-control" type="email" name="email_A" required />
                        </div>
                    </div>

                    <div class="form-floating mb-3" id="optionTiempo2">
                        <div>
                            <label>Seleccione el Tiempo de Trabajo del Funcionario</label>
                            <select class="form-control" name="tiempo_trabajo">
                                <option value="8">Tiempo Completo</option>
                                <option value="4">Medio Tiempo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-floating mb-3" id="opcionesFuncionario">
                        <div>
                            <label>Fecha Ingreso</label>
                            <input class="form-control" type="date" name="fecha_ingreso" require/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary cerrarModal" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Registrar Funcionario Nuevo</button>
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


    function cargarDatos(button) {
        var modal = document.querySelector('#Editar_datos');
        var cliente_id = modal.querySelector('[name="cliente_id"]');
        var cedulaInput = modal.querySelector('[name="cedula"]');
        var nombresInput = modal.querySelector('[name="nombres"]');
        var apellidosInput = modal.querySelector('[name="apellidos"]');
        var emailInput = modal.querySelector('[name="email_A"]');
        var fecha_ingresoInput = modal.querySelector('[name="fecha_ingreso"]');
        var tiempo_trabajoInput = modal.querySelector('[name="tiempo_trabajo"]');

        var userId = button.getAttribute('data-id');
        var cedula = button.getAttribute('data-cedula');
        var username = button.getAttribute('data-name');
        var lastname = button.getAttribute('data-lastname');
        var email = button.getAttribute('data-email');
        var fecha_ingreso = button.getAttribute('data-fecha_ingreso');
        var tiempo_trabajo = button.getAttribute('data-tiempo_trabajo');

        // Rellenar los campos del modal con los datos obtenidos
        cliente_id.value = userId;
        cedulaInput.value = cedula;
        nombresInput.value = username;
        apellidosInput.value = lastname;
        emailInput.value = email;
        fecha_ingresoInput.value = fecha_ingreso;
        tiempo_trabajoInput.value = tiempo_trabajo;
    }


    function eliminar(button) {
        var userId = button.getAttribute('data-id');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('cliente_id').value = userId;
    }

</script>

<?php
include_once("../plantilla/footer.php")
?>