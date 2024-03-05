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

$titulo = "Permisos Registrados";
include_once("../plantilla/header.php")
?>
<?php
include_once  "../conexion.php";
include_once  "../funciones.php";
$respuesta = soli_registradas($pdo);
?>

<div class="container-fluid mt-5">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Permisos registrados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive crud-table">
                <table class="table table-bordered" id="tabla_permisos_registrados">
                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Funcionario</th>
                            <th>Fecha emitida</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (empty($respuesta)) {
                            echo "";

                        }else {
                            $fecha_actual = date('Y-m-d');
                            foreach ($respuesta as $key => $valor){
                                $id_usuarios  = $valor ["id_usuarios"];
                                $id_permiso  = $valor ["id_permisos"];
                                $nombres  = $valor ["nombres"];
                                $apellidos  = $valor ["apellidos"];
                                $cedula_user  = $valor ["cedula"];
                                $fecha_permiso = $valor ["fecha_permiso"];
                                $motivo_permiso = $valor ["motivo_permiso"];
                                $permiso_aceptado = $valor['permiso_aceptado'];
                        ?>
                        <tr>
                            <td><?=  $cedula_user ;?></td>
                            <td><?=  $nombres . " " . $apellidos ;?></td>
                            <td><?=  $fecha_permiso ;?></td>
                            <td>
                                <?php if ($permiso_aceptado == 3): ?>
                                    <button class="btn btn-success" title="Aceptado" data-toggle="modal"
                                    data-target="#cancelarSolicitud" data-id="<?= $id_permiso ?>" onclick="cancelar(this)"><i class="fa-solid fa-check"></i></button>
                                <?php endif; ?>
                                <form action="../datos_individuales" method="POST" class="d-inline-block m-1">
                                    <input type="hidden" name="id_permisos" value=" <?= $id_permiso ?>">
                                    <button class="btn btn-info m-1" title="Ver los datos de esta solicitud">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                    <?php
                        };
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="cancelarSolicitud" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdminLabel">Cancelar permiso registrado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea cancelar el registro de esta solicitud ?</p>
                <br>
                <div class="alert alert-danger" role="alert">
                Si el permiso es cancelado se eliminara el archivo subido del usuario que registro el permiso
                </div>
                <form id="cancelarS" action="<?php echo RUTA_ABSOLUTA ?>procesar" method="POST">
                    <input type="hidden" name="id_cancelar" id="id_cancelar" value ="" />
                    <input class="form-control" type="hidden" name="cancelar_registro" value ="1" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="cancelarS" class="btn btn-primary">Aceptar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<script>
    function cancelar(button) {
        var userId = button.getAttribute('data-id');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('id_cancelar').value = userId;
        console.log(userId);
    }
</script>

<?php include_once("../plantilla/footer.php")?>
