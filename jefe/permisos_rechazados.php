<?php
include_once "../redirection.php";
include_once "../flash_messages.php";
session_start();

if (!isset($_SESSION['id_usuarios'])) {
    redirect(RUTA_ABSOLUTA . 'logout');
}
$cedulaJefe = $_SESSION['cedula'];
$nombreJefe = $_SESSION['nombres'];
$apellidosJefe = $_SESSION['apellidos'];
$rol = $_SESSION['rol'];

if ($rol != ROL_JEFE) {
   redirect(RUTA_ABSOLUTA . "logout");
}

$message = '';
$type = '';
$flash_message = display_flash_message();

if (isset($flash_message)) {
    $message = $flash_message['message'];
    $type = $flash_message['type'];
}

$titulo = "Permisos Rechazados";
include_once("../plantilla/hedeerDos.php");
?>
<?php
include_once  "../conexion.php";
include_once  "../funciones.php";
$respuesta = soli_rechazadas($pdo);
?>
<div class="container-fluid mt-5">
    <!-- Page Heading -->
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Permisos rechazados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive crud-table">
                <table class="table table-bordered" id="tabla_permisos_aceptados">
                    <thead>
                        <tr>
                            <th>N° Cédula</th>
                            <th>Nombres</th>
                            <th>Fecha emitida</th>
                            <th>Tipo de permiso</th>
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
                                $motivo_permiso = str_replace('_', ' ', $motivo_permiso);
                                $permiso_aceptado = $valor['permiso_aceptado'];

                        ?>
                        <tr>
                            <td><?=  $cedula_user ;?></td>
                            <td><?=  $nombres . " " . $apellidos ;?></td>
                            <td><?=  $fecha_permiso ;?></td>
                            <td><?=  $motivo_permiso ;?></td>
                            <td>
                                <?php if ($permiso_aceptado == 2): ?>
                                    <button class="btn btn-danger" title="Permiso Rechazado" data-toggle="modal" data-target="#aceptarSolicitud" data-id="<?= $id_permiso ?>" onclick="aceptar(this)"><i class="fa-solid fa-xmark"></i></button>
                                <?php endif; ?>
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

<!-- Modal aceptar Solicitud -->
<div class="modal fade" id="aceptarSolicitud" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdminLabel">Cancelar permiso rechazado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea cancelar esta solicitud ?</p>
                <form id="AceptarS" action="<?php echo RUTA_ABSOLUTA ?>jefe/procesarSolicitud" method="POST">
                    <input type="hidden" name="id_aprueba" id="id_aprueba" value ="" />
                    <input class="form-control" type="hidden" name="aprobarRechazo" value ="0" />
                    <input class="form-control" type="hidden" name="aprobar_rechazo" value ="" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="AceptarS" class="btn btn-primary">Aceptar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(".cerrarModal").click(function(){
        $("#registrar_permisos").modal('hide')
    });
    function aprobar(button) {
        var userId = button.getAttribute('data-registrar');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('id_registrar').value = userId;
    }
    function aceptar(button) {
        var userId = button.getAttribute('data-id');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('id_aprueba').value = userId;
    }
</script>
<?php include_once("../plantilla/footer.php")?>
