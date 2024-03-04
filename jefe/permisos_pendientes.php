<?php
include_once "../redirection.php";
include_once "../flash_messages.php";
session_start();

if (!isset($_SESSION['id_usuarios'])) {
    redirect(RUTA_ABSOLUTA . 'logout');
}
$id_aprueba = $_SESSION['id_usuarios'];
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
include_once("../plantilla/header.php")
?>

<div class="container-fluid mt-5">
<?php
include_once  "../conexion.php";
include_once  "../funciones.php";
$respuesta = soli_no_aceptadas($pdo);
?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Permisos solicitados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered crud-table" id="tabla_permisos_pendientes">
                    <thead>
                        <tr>
                            <th>Cédula </th>
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
                                $permiso_aceptado = $valor['permiso_aceptado'];

                                $ruta_solicita = $valor['ruta_solicita'];
                                $ruta_aprueba = $valor['ruta_aprueba'];
                                $rutaSolicita = verificarRuta($ruta_solicita);
                                $rutaAprueba = verificarRuta($ruta_aprueba);

                                if (!empty($ruta_solicita) && $permiso_aceptado == 0) {
                                    $ruta_solicita = '<a class="btn btn-success m-1" title="Archivo del usuario" href="' . RUTA_ABSOLUTA . $rutaSolicita .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';
                                    $ruta_aprueba = null;

                                    $ruta_registra = null;

                                    $permiso_aceptado = null;

                                    $btnAprobar = '<button class="btn btn-primary m-1" title="Aprobar permiso"  data-toggle="modal" data-target="#aprobar_soli" data-id_permiso="'. $id_permiso .'" onclick="aprobar(this)"><i class="fa-solid fa-check"></i></button>';
                                    $btnRechazar = '<button class="btn btn-danger m-1" title="Rechazar permiso" data-toggle="modal" data-target="#rechazar" data-id_rechazo="'. $id_permiso .'" onclick="rechazo(this)"><i class="fa-solid fa-xmark"></i></button>';

                                    $eliminar = null;
                                }elseif ($permiso_aceptado == 1) {
                                    $ruta_solicita = '<a class="btn btn-success m-1" title="Solicitud firmada por el usuario" href="' . RUTA_ABSOLUTA . $ruta_solicita .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                    $ruta_aprueba = '<a class="btn btn-primary m-1" title="Solicitud firmada por el jefe" href="' . RUTA_ABSOLUTA . $rutaAprueba .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                    $ruta_registra = null;

                                    $permiso_aceptado = ' <button class="btn btn-success" title="Permiso aprobado" data-toggle="modal"
                                    data-target="#cancelarSolicitud" data-id="'. $id_permiso .'" onclick="cancelar(this)"><i class="fa-solid fa-check"></i></button>';
                                    $eliminar = null;
                                }elseif ($permiso_aceptado == 2) {
                                    $permiso_aceptado = '<button class="btn btn-danger" title="Permiso rechazado" ><i class="fa-solid fa-xmark"></i></button>';

                                    $ruta_solicita = null;

                                    $ruta_aprueba = null;

                                    $ruta_registra = null;

                                    $eliminar = null;
                                }elseif ($permiso_aceptado == 3) {
                                    $permiso_aceptado = '<button class="btn btn-primary" title="Permiso registrado" ><i class="fa-solid fa-check"></i></button>';

                                    $ruta_solicita = '<a class="btn btn-success m-1" title="Solicitud firmada por el usuario" href="' . RUTA_ABSOLUTA . $rutaSolicita .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                    $ruta_aprueba = '<a class="btn btn-primary m-1" title="Solicitud firmada por el jefe" href="' . RUTA_ABSOLUTA . $rutaAprueba .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                    $ruta_registra = '<a class="btn btn-warning m-1" title="Solicitud registrada por talento humano" href="' . RUTA_ABSOLUTA . $ruta_registra .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                    $eliminar = null;
                                }elseif ($permiso_aceptado == 0) {
                                    $permiso_aceptado = null;
                                    $btnAprobar = null;
                                    $btnRechazar = null;
                                    $eliminar = '<button class="btn btn-danger m-1" title="Eliminar" data-toggle="modal"
                                    data-target="#Eliminar" data-id="'. $id_permiso .'" onclick="eliminar(this)">
                                    <i class="fas fa-trash"></i>
                                    </button>';
                                }

                        ?>
                        <tr>
                            <td><?=  $cedula_user ;?></td>
                            <td><?=  $nombres . " " . $apellidos ;?></td>
                            <td><?=  $fecha_permiso ;?></td>

                            <td>
                                <?=  $permiso_aceptado ;?>
                                <?=  $btnAprobar ;?>
                                <?=  $btnRechazar ;?>
                                <form action="../datos_individuales" method="POST" class="d-inline-block m-1">
                                    <input type="hidden" name="id_permisos" value=" <?= $id_permiso ?>">
                                    <button class="btn btn-info m-1" title="Datos generados">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </form>
                                <?=  $ruta_solicita ;?>
                                <?=  $ruta_aprueba ;?>
                                <?=  $ruta_registra ;?>

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


<!-- Modal para rechazar -->
<div class="modal fade" id="rechazar" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdminLabel">Rechazar permiso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="eliminarForm" action="<?php echo RUTA_ABSOLUTA ?>jefe/procesarSolicitud" method="post">
                    <div class="form-floating mb-3">
                        <div>
                            <label>Por favor ingrese el motivo del rechazo del permiso</label>
                            <input class="form-control" type="text" name="rechazo_motivo"/>
                            <input type="hidden" name="id_rechazo" id="id_rechazo" value ="" />
                            <input type="hidden" name="rechazo" value ="2" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="eliminarForm" class="btn btn-primary">Continuar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de aprobar la solicitud-->
<div class="modal fade" id="aprobar_soli" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdminLabel">Aprobar permiso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="aprobarForm" action="<?php echo RUTA_ABSOLUTA ?>jefe/procesarSolicitud" method="post" enctype="multipart/form-data">

                    <input class="form-control" type="hidden" name="aprobar" value ="1" />

                    <div class="form-floating mb-3">
                        <div>
                            <input class="form-control" name="user" type="hidden" value="<?= $nombreJefe . " " . $apellidosJefe ?>" />
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <div>
                            <label>Descripción del archivo</label>
                            <textarea class="form-control" name="archivoDescripcion" id="archivoDescripcion" cols="30" rows="2" required></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="id_permiso" id="id_permisoJefe" value="">
                    <input type="hidden" name="id_aprueba" id="id_aprueba" value="<?= $id_aprueba ?>">
                    <label>Subir el archivo del permiso con su firma</label>
                    <input class="form-control" type="file" name="archivo" id="archivo" required>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Aprobar</button>
                        <button type="button" class="btn btn-danger cerrarModal" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal del archivo escaneado -->
<div class="modal fade" id="subirEscaneado" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdminLabel">Subir archivo escaneado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="subirForm" action="<?php echo RUTA_ABSOLUTA ?>jefe/archivosPendientes" method="post" enctype="multipart/form-data">
                    <div class="form-floating mb-3">
                        <div>
                            <label>Descripción del archivo</label>
                            <textarea class="form-control" name="archivoDescripcion" id="archivoDescripcion" cols="30" rows="2" required></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="id_permiso" id="id_permiso" value="">
                    <input type="hidden" name="id_aprueba" id="id_aprueba" value="<?= $id_aprueba ?>">
                    <label>Archivo firmado por el solicitante</label>
                    <input class="form-control" type="file" name="archivo" id="archivo" required>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="subirForm" class="btn btn-primary">Subir archivo</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(".cerrarModal").click(function(){
        $("#registrar_permisos").modal('hide')
    });

    function rechazo(button) {
        var userId = button.getAttribute('data-id_rechazo');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('id_rechazo').value = userId;
    }
    function aprobar(button) {
        var userId = button.getAttribute('data-id_permiso');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('id_permisoJefe').value = userId;
    }
    function subirArchivo(button) {
        var permisoId = button.getAttribute('data-id');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('id_permisoSubir').value = permisoId;
    }
</script>
<?php include_once("../plantilla/footer.php")?>
