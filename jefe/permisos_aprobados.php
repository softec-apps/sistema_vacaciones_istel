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

$titulo = "Permisos";
include_once("../plantilla/hedeerDos.php");
?>
<?php
include_once  "../conexion.php";
include_once  "../funciones.php";
$respuesta = soliarchivosAceptadas($pdo);
?>
<div class="container-fluid mt-5">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Permisos aprobados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive crud-table">
                <table class="table table-bordered" id="tabla_permisos_aceptados">
                    <thead>
                        <tr>
                            <th>N° Cédula </th>
                            <th>Nombres</th>
                            <th>Fecha emitida</th>
                            <th>Tipo de permiso</th>
                            <th>Acciones</th>
                            <th>Archivos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (empty($respuesta)) {
                            echo "";

                        }else {
                            $fecha_actual = date('Y-m-d');
                            foreach ($respuesta as $key => $valor){
                                $id_permiso  = $valor ["id_permisos"];
                                $nombres  = $valor ["nombres"];
                                $apellidos  = $valor ["apellidos"];
                                $cedula_user  = $valor ["cedula"];
                                $fecha_permiso = $valor ["fecha_permiso"];
                                $motivo_permiso = $valor ["motivo_permiso"];
                                $motivo_permiso = str_replace('_', ' ', $motivo_permiso);
                                $permiso_aceptado = $valor['permiso_aceptado'];

                                $ruta_solicita = $valor['ruta_solicita'];
                                $ruta_aprueba = $valor['ruta_aprueba'];

                                $rutaSolicita = verificarRuta($ruta_solicita);
                                $rutaAprueba = verificarRuta($ruta_aprueba);
                        ?>
                        <tr>
                            <td><?=  $cedula_user ;?></td>
                            <td><?=  $nombres . " " . $apellidos ;?></td>
                            <td><?=  $fecha_permiso ;?></td>
                            <td><?=  $motivo_permiso ;?></td>
                            <td>
                                <?php if ($permiso_aceptado == 1): ?>
                                    <button class="btn btn-success" title="Aceptado" data-toggle="modal" data-target="#cancelarSolicitud" data-id="<?= $id_permiso ?>" onclick="cancelar(this)"><i class="fa-solid fa-check"></i></button>
                                <?php elseif ($permiso_aceptado == 3): ?>
                                    <button class="btn btn-primary m-1" title="Ya registrado"><i class="fa-solid fa-check"></i></button>
                                <?php endif; ?>

                                <form action="../datos_individuales" method="POST" class="d-inline-block m-1">
                                    <input type="hidden" name="id_permisos" value="<?= $id_permiso ?>">
                                    <button class="btn btn-info" title="Ver los datos de esta solicitud"><i class="bi bi-eye"></i></button>
                                </form>
                            </td>

                            <td>
                                <?php

                                if (!empty($ruta_solicita)) {
                                    echo '<a class="btn btn-success m-1" title="Solicitud firmada por el funcionario" href="' . RUTA_ABSOLUTA . $rutaSolicita . '" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';
                                } elseif ($ruta_solicita == 'error') {
                                    echo '<button class="btn btn-danger m-1" title="Archivo no encontrado"><i class="fa-solid fa-circle-exclamation"></i></button>';
                                }

                                if (!empty($ruta_aprueba)) {
                                    echo '<a class="btn btn-primary m-1" title="Solicitud firmada por el jefe supervisor" href="' . RUTA_ABSOLUTA . $rutaAprueba . '" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';
                                }elseif ($ruta_aprueba == 'error') {
                                    echo '<button class="btn btn-danger m-1" title="Archivo no encontrado"><i class="fa-solid fa-circle-exclamation"></i></button>';
                                }

                                ?>
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
<!-- Modal para cancelar una solicitud -->
<div class="modal fade" id="cancelarSolicitud" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdminLabel">Confirmar cancelación del permiso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea cancelar esta solicitud ?</p>
                <p>Recuerde que si cancela la solicitud este permiso se moverá a la sección de "Solicitudes"</p>
                <div class="alert alert-danger" role="alert">
                El archivo de este permiso que subió se eliminara y su acción quedara guardada
                </div>

                <form id="cancelarS" action="<?php echo RUTA_ABSOLUTA ?>jefe/procesarSolicitud" method="POST">
                    <input type="hidden" name="id_cancelar" id="id_cancelar" value =""/>
                    <input class="form-control" type="hidden" name="cancelar" value ="0" />
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
    $(".cerrarModal").click(function(){
        $("#registrar_permisos").modal('hide')
    });
    function aprobar(button) {
        var userId = button.getAttribute('data-registrar');
        document.getElementById('id_registrar').value = userId;
    }
    function cancelar(button) {
        var userId = button.getAttribute('data-id');
        document.getElementById('id_cancelar').value = userId;
    }
</script>
<?php include_once("../plantilla/footer.php")?>
