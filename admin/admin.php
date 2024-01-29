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

if ($rol != ROL_ADMIN) {
   redirect(RUTA_ABSOLUTA . "logout");
}
$message = '';
$type = '';
$flash_message = display_flash_message();

if (isset($flash_message)) {
    $message = $flash_message['message'];
    $type = $flash_message['type'];
}
$titulo = "Administradores";
include_once("../plantilla/header.php")
?>

<div class="container-fluid mt-5">

<?php

include_once "../funciones.php";
include_once "../conexion.php";

$resultados_users = mostrarUsuarios($pdo);

?>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Administradores</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered crud-table" id="tabla_admininstradores">
                        <thead>
                            <tr>
                                <th>Cedula</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Fecha ingreso</th>
                                <th class="exclude">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($resultados_users as $key => $valor){
                                $id_usuarios = $valor ["id_usuarios"];
                                $cedula_usuarios = $valor['cedula'];
                                $nombres_usuarios = $valor['nombres'];
                                $apellidos_usuarios = $valor['apellidos'];
                                $usuario = $valor['usuario'];
                                $clave = $valor['contraseña'];
                                $email_usuarios = $valor['email'];
                                $rol_usuarios = $valor['rol'];
                                $fecha_ingreso = $valor['fecha_ingreso'];
                                $tiempo_trabajo = $valor['tiempo_trabajo'];
                            ?>
                            <tr>

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
                                        data-toggle="modal" data-target="#Editar_datos" data-id="<?= $id_usuarios ?>" data-cedula="<?= $cedula_usuarios?>" data-name="<?= $nombres_usuarios ?>" data-lastname="<?= $apellidos_usuarios ?>" data-user="<?= $usuario ?>" data-password="<?= $clave ?>" data-email="<?= $email_usuarios ?>" data-rol="<?= $rol_usuarios ?>" data-fecha_ingreso="<?= $fecha_ingreso ?>" data-tiempo_trabajo="<?= $tiempo_trabajo ?>" onclick="cargarDatos(this)">
                                        <i class="fa-solid fa-pencil"></i>
                                    </button>

                                    <button class="btn btn-danger" title="Eliminar" data-toggle="modal"
                                        data-target="#Eliminar" data-id="<?= $id_usuarios ?>" onclick="eliminar(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php
                                };
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
                    <form action="<?php echo RUTA_ABSOLUTA ?>admin/actualizar_users" method="post">
                        <input type="hidden" name="cliente_id" value="">

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese la nueva cedula</label>
                                <input class="form-control" name="cedula" type="text"/>
                            </div>
                        </div>


                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese los nombres</label>
                                <input class="form-control" name="nombres" type="text"/>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese los apellidos</label>
                                <input class="form-control" name="apellidos" type="text"/>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese un usuario </label>
                                <input class="form-control" name="user_A" type="text" required />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese la contraseña para el usuario</label>
                                <input class="form-control" type="text" name="password_A" required />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese el Email del Usuario</label>
                                <input class="form-control" type="email" name="email_A"/>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <div>
                                <label>Seleccione el rol del usuario</label>
                                <select class="form-control" name="roles">
                                    <option value="admin">Administrador</option>
                                    <option value="jefe">Jefe o supervisor</option>
                                    <option value="Talento_Humano">Talentos Humanos</option>
                                    <option value="Funcionario">Funcionario</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-floating mb-3" id="optionTiempo" style="display: none;">
                            <div>
                                <label>Seleccione el Tiempo de Trabajo del Funcionario</label>
                                <select class="form-control" name="tiempo_trabajo">
                                    <option value="8">Tiempo Completo</option>
                                    <option value="4">Medio Tiempo</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-floating mb-3" id="opcionesFuncionario" style="display: none;">
                            <!-- Aquí coloca las opciones adicionales para el rol de Funcionario -->
                            <div>
                                <label>fecha_ingreso</label>
                                <input class="form-control" type="date" name="fecha_ingreso" />
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



<!-- Modal para registrar Usuarios -->
<div class="modal fade" id="registrar_administradores" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Registrar Nuevo Usuario</h5>
                <button type="button" class="close cerrarModal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form method="POST" action="<?php echo RUTA_ABSOLUTA ?>admin/registrar_users">


                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese la cedula del nuevo usuario</label>
                            <input class="form-control" name="cedula" type="text" required />
                        </div>
                    </div>


                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese los nombre del nuevo usuario</label>
                            <input class="form-control" name="nombres" type="text" required />
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese los apellidos del nuevo usuario</label>
                            <input class="form-control" name="apellidos" type="text" required />
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese un usuario </label>
                            <input class="form-control" name="user_A" type="text" required />
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese la contraseña para el usuario</label>
                            <input class="form-control" type="text" name="password_A" required />
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese el Email del Usuario</label>
                            <input class="form-control" type="email" name="email_A" required />
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <div>
                            <label>Seleccione el rol del usuario</label>
                            <select class="form-control" id="roles" name="roles1"required onchange="mostrarOpciones()">
                                <option value="admin">Administrador</option>
                                <option value="jefe">Jefe o supervisor</option>
                                <option value="Talento_Humano">Talentos Humanos</option>
                                <option value="Funcionario">Funcionario</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-floating mb-3" id="optionTiempo2" style="display: none;">
                        <div>
                            <label>Seleccione el Tiempo de Trabajo del Funcionario</label>
                            <select class="form-control" name="tiempo_trabajo">
                                <option value="8">Tiempo Completo</option>
                                <option value="4">Medio Tiempo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-floating mb-3" id="function_option" style="display: none;">
                        <!-- Aquí coloca las opciones adicionales para el rol de Funcionario -->
                        <div>
                            <label>fecha_ingreso</label>
                            <input class="form-control" type="date" name="fecha_ingreso" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary cerrarModal" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Registrar nuevo Usuario</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        // Manejar el cambio en la selección del rol
        $('[name="roles"]').on('change', function() {
            var selectedRol = $(this).val();
            // Mostrar u ocultar las opciones adicionales según el rol seleccionado
            if (selectedRol === 'Funcionario') {
                $('#opcionesFuncionario').show();
                $('#optionTiempo').show();
            } else {
                $('#opcionesFuncionario').hide();
                $('#optionTiempo').hide();
            }
        });
    });

    function mostrarOpciones() {
        var seleccion = document.getElementById("roles").value;
        var function_option = document.getElementById("function_option");
        var optionTiempo = document.getElementById("optionTiempo2");

        if (seleccion === "Funcionario") {
            function_option.style.display = "block";
            optionTiempo.style.display = "block";
        } else {
            function_option.style.display = "none";
            optionTiempo.style.display = "none";
        }
    }


    $(".cerrarModal").click(function(){
        $("#registrar_administradores").modal('hide')
    });

    function cargarDatos(button) {
        var modal = document.querySelector('#Editar_datos');
        var cliente_id = modal.querySelector('[name="cliente_id"]');
        var cedulaInput = modal.querySelector('[name="cedula"]');
        var nombresInput = modal.querySelector('[name="nombres"]');
        var apellidosInput = modal.querySelector('[name="apellidos"]');
        var user_AInput = modal.querySelector('[name="user_A"]');
        var password_AInput = modal.querySelector('[name="password_A"]');
        var emailInput = modal.querySelector('[name="email_A"]');
        var rolInput = modal.querySelector('[name="roles"]');
        var tiempo_trabajoInput = modal.querySelector('[name="tiempo_trabajo"]');
        var fecha_ingresoInput = modal.querySelector('[name="fecha_ingreso"]');

        var userId = button.getAttribute('data-id');
        var cedula = button.getAttribute('data-cedula');
        var username = button.getAttribute('data-name');
        var lastname = button.getAttribute('data-lastname');
        var user = button.getAttribute('data-user');
        var password = button.getAttribute('data-password');
        var email = button.getAttribute('data-email');
        var rol = button.getAttribute('data-rol');
        var tiempo_trabajo = button.getAttribute('data-tiempo_trabajo');
        var fecha_ingreso = button.getAttribute('data-fecha_ingreso');

        // Rellenar los campos del modal con los datos obtenidos
        cliente_id.value = userId;
        cedulaInput.value = cedula;
        nombresInput.value = username;
        apellidosInput.value = lastname;
        user_AInput.value = user;
        password_AInput.value = password;
        emailInput.value = email;
        rolInput.value = rol;
        // Mostrar u ocultar las opciones adicionales según el rol seleccionado
        if (rol === 'Funcionario') {
            $('#opcionesFuncionario').show();
            $('#optionTiempo').show();
        } else {
            $('#opcionesFuncionario').hide();
            $('#optionTiempo').hide();
        }
        tiempo_trabajoInput.value = tiempo_trabajo;
        fecha_ingresoInput.value = fecha_ingreso;
    }


    function eliminar(button) {
        var userId = button.getAttribute('data-id');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('cliente_id').value = userId;
    }

</script>
<?php include_once("../plantilla/footer.php")?>
