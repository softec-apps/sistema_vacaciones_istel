<?php
include_once "../redirection.php";
include_once "../flash_messages.php";
session_start();

if (!isset($_SESSION['id_usuarios'])) {
    redirect(RUTA_ABSOLUTA . 'logout');
}
$id_user = $_SESSION['id_usuarios'];
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

$resultados_users = mostrarUsuarios($pdo,$id_user);

?>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Agregar Usuarios</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered crud-table" id="tabla_admininstradores">
                        <thead>
                            <tr>
                                <th>Cédula</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Correo electrónico </th>
                                <th>Rol</th>
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
                                $rolUsuarios = $valor['rol'];
                                $rol_usuarios = ucwords($rolUsuarios);
                                // Eliminar guiones bajos
                                $rol_usuarios = str_replace('_', ' ', $rol_usuarios);
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
                                    <button class="btn btn-primary m-1" title="Editar datos del usuario"
                                        data-toggle="modal" data-target="#Editar_datos" data-id="<?= $id_usuarios ?>" data-cedula="<?= $cedula_usuarios?>" data-name="<?= $nombres_usuarios ?>" data-lastname="<?= $apellidos_usuarios ?>" data-user="<?= $usuario ?>" data-email="<?= $email_usuarios ?>" data-rol="<?= $rolUsuarios ?>" data-fecha_ingreso="<?= $fecha_ingreso ?>" data-tiempo_trabajo="<?= $tiempo_trabajo ?>" onclick="cargarDatos(this)">
                                        <i class="fa-solid fa-pencil"></i>
                                    </button>
                                    <button class="btn btn-warning" title="Recuperar contraseña" data-toggle="modal"
                                        data-target="#Recuperar" data-id="<?= $id_usuarios ?>" onclick="recuperar(this)">
                                        <i class="fa-solid fa-key"></i>
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

    <!-- Modal recuperar contraseña -->
    <div class="modal fade" id="Recuperar" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdminLabel">Recuperar contraseña</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- <p></p> -->
                    <form id="recuperarForm" action="<?php echo RUTA_ABSOLUTA ?>admin/recovePassword" method="post">
                        <input type="hidden" name="user_id" id="user_id" value="">
                        <div class="row mb-3">
                            <div class="col">
                                <label>Nueva contraseña </label>
                                <input class="form-control mb-3" type="text" name="recuperarClave" id="recuperarClave" required>
                                <label>Repetir la contraseña</label>
                                <input class="form-control" type="password" name="repetirClave" id="repetirClave" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" form="recuperarForm" class="btn btn-primary">Recuperar contraseña</button>
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
                    <form action="<?php echo RUTA_ABSOLUTA ?>admin/actualizar_users" method="post" onsubmit="return validarFormulario();">
                        <input type="hidden" name="cliente_id" value="">

                        <div class="row mb-3">
                            <div class="col">
                                <label>Número de cédula </label>
                                <input class="form-control" name="cedula" type="text" pattern="\d{10}" title="Ingrese exactamente los 10 números de la cédula" required/>
                            </div>
                            <div class="col">
                                <label>Nombres</label>
                                <input class="form-control" name="nombres" type="text" required/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label>Apellidos</label>
                                <input class="form-control" name="apellidos" type="text" required/>
                            </div>
                            <div class="col">
                                <label>Nombre de usuario </label>
                                <input class="form-control" name="user_A" type="text" required />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label>Correo electrónico </label>
                                <input class="form-control" type="email" name="email_A" required/>
                            </div>
                            <div class="col">
                                <label>Seleccione el rol del usuario</label>
                                <select class="form-select" name="roles" required>
                                    <option value="admin">Administrador</option>
                                    <option value="jefe">Jefe o supervisor</option>
                                    <option value="Talento_Humano">Talentos Humanos</option>
                                    <option value="Funcionario">Funcionario</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col" id="optionTiempo">
                                <label>Modalidad de Trabajo</label>
                                <select class="form-select" name="tiempo_trabajo" required>
                                    <option value="8">Tiempo Completo</option>
                                    <option value="4">Medio Tiempo</option>
                                </select>
                            </div>
                            <div class="col" id="opcionesFuncionario">
                                <div>
                                    <label>Seleccione la fecha de ingreso</label>
                                    <input class="form-control" type="date" name="fecha_ingreso" required/>
                                </div>
                            </div>
                        </div>


                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Actualizar información</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
                <h5 class="modal-title" id="exampleModalLabel">Registrar nuevo usuario</h5>
                <button type="button" class="close cerrarModal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form  method="POST" action="<?php echo RUTA_ABSOLUTA ?>admin/registrar_users"  onsubmit="return validarFormularioRegistrar();">

                    <div class="row mb-3">
                        <div class="col">
                            <label>Número  de cédula  </label>
                            <input class="form-control" name="cedula" type="text" pattern="\d{10}" title="Ingrese exactamente los 10 números de la cédula" required />
                        </div>
                        <div class="col">
                            <label>Nombres </label>
                            <input class="form-control" name="nombres" type="text" required />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label>Apellidos </label>
                            <input class="form-control" name="apellidos" type="text" required />
                        </div>
                        <div class="col">
                            <label>Nombre de usuario </label>
                            <input class="form-control" name="user_A" type="text" required />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label>Contraseña del usuario </label>
                            <input class="form-control" type="text" name="password_A" required />
                        </div>
                        <div class="col">
                            <label>Correo electrónico</label>
                            <input class="form-control" type="email" name="email_A" required />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label>Seleccione el rol del usuario</label>
                            <select class="form-select" id="roles" name="roles1"required onchange="mostrarOpciones()">
                                <option value="admin">Administrador</option>
                                <option value="jefe">Jefe o supervisor</option>
                                <option value="Talento_Humano">Talentos Humanos</option>
                                <option value="Funcionario">Funcionario</option>
                            </select>
                        </div>
                        <div class="col" id="optionTiempo2" style="display: none;">
                            <label>Modalidad de trabajo </label>
                            <select class="form-select" name="tiempo_trabajo">
                                <option selected>Seleccione la modalidad</option>
                                <option value="8">Tiempo Completo</option>
                                <option value="4">Medio Tiempo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-floating mb-3" id="function_option" style="display: none;">
                        <div>
                            <label>Seleccione la fecha de ingreso</label>
                            <input class="form-control" type="date" name="fecha_ingreso" id="fecha_ingreso" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Registrar nuevo usuario</button>
                        <button type="button" class="btn btn-secondary cerrarModal" data-dismiss="modal">Cerrar</button>
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
        var fecha_ingresoInput = document.getElementById("fecha_ingreso");

        if (seleccion === "Funcionario") {
            function_option.style.display = "block";
            optionTiempo.style.display = "block";
            // Agregar el atributo required al input cuando el rol es Funcionario
            fecha_ingresoInput.setAttribute("required", "required");
        } else {
            function_option.style.display = "none";
            optionTiempo.style.display = "none";
            // Eliminar el atributo required del input cuando el rol no es Funcionario
            fecha_ingresoInput.removeAttribute("required");
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
        emailInput.value = email;
        rolInput.value = rol;
        // Mostrar u ocultar las opciones adicionales según el rol seleccionado
        if (rol === 'Funcionario') {
            $('#opcionesFuncionario').show();
            $('#optionTiempo').show();
            document.querySelector('input[name="fecha_ingreso"]').required = true;
        } else {
            $('#opcionesFuncionario').hide();
            $('#optionTiempo').hide();
            // Desactivar el requerimiento del campo de entrada
            document.querySelector('input[name="fecha_ingreso"]').required = false;
        }
        tiempo_trabajoInput.value = tiempo_trabajo;
        fecha_ingresoInput.value = fecha_ingreso;
    }


    function eliminar(button) {
        var userId = button.getAttribute('data-id');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('cliente_id').value = userId;
    }
    function recuperar(button) {
        var userId = button.getAttribute('data-id');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('user_id').value = userId;
    }
    function validarFormulario() {
        var rolInput = document.querySelector('[name="roles"]');
        var fechaIngresoInput = document.querySelector('[name="fecha_ingreso"]');

        // Obtener el valor del rol seleccionado
        var rolSeleccionado = rolInput.value;

        // Si el rol es "Funcionario"
        if (rolSeleccionado === 'Funcionario') {
            // Obtener la fecha ingresada por el usuario
            var fechaIngreso = new Date(fechaIngresoInput.value);

            // Obtener la fecha actual
            var fechaActual = new Date();

            // Verificar si la fecha está vacía o es superior al año actual
            if (!fechaIngresoInput.value.trim() || fechaIngreso > fechaActual) {
                alert('Por favor, ingrese una fecha de ingreso válida para el Funcionario.');
                return false; // Evita que el formulario se envíe
            }
        }

        // Si todo está bien, permitir el envío del formulario
        return true;
    }

    function validarFormularioRegistrar() {
        var rolInput = document.querySelector('[name="roles1"]');
        var fechaIngresoInput = document.querySelector('#fecha_ingreso');

        // Obtener el valor del rol seleccionado
        var rolSeleccionado = rolInput.value;
        var fechaSeleccionada = fechaIngresoInput.value;

        // Si el rol es "Funcionario"
        if (rolSeleccionado === 'Funcionario') {
            // Obtener la fecha ingresada por el usuario
            var fechaIngreso = new Date(Date.parse(fechaIngresoInput.value));

            // Obtener la fecha actual
            var fechaActual = new Date();

            // Verificar si la fecha está vacía o es superior al año actual
            if (fechaIngreso > fechaActual) {
                alert('Por favor, ingrese una fecha de ingreso válida para el usuario');
                return false;
            }else if(!fechaIngresoInput.value.trim()){
                alert('Fecha vacía ');
                return false;
            }
        }

        // Si todo está bien, permitir el envío del formulario
        return true;
    }
</script>
<?php include_once("../plantilla/footer.php")?>
