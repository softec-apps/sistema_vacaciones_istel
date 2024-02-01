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
$titulo = "Permisos";
include_once("../plantilla/header.php")
?>

<div class="container-fluid mt-5">
<?php
include_once  "../conexion.php";
include_once  "../funciones.php";
$respuesta = soli_no_aceptadas($pdo);
?>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Permisos Pendientes</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Permisos Pendientes</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered crud-table" id="tabla_permisos_pendientes">
                    <thead>
                        <tr>
                            <th>Cedula</th>
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
                                $provincia = $valor ["provincia"];
                                $regimen = $valor ["regimen"];
                                $coordinacion_zonal = $valor ["coordinacion_zonal"];
                                $direccion_unidad = $valor ["direccion_unidad"];
                                $fecha_permiso = $valor ["fecha_permiso"];
                                $motivo_permiso = $valor ["motivo_permiso"];
                                $tiempoLimite_motivo = $valor ["tiempo_motivo"];
                                $desc_motivo = $valor['desc_motivo'];
                                $dias_solicitados = $valor['dias_solicitados'];
                                $horas_solicitadas = (empty(strtotime($valor['horas_solicitadas'])) || $valor['horas_solicitadas'] == '00:00:00') ? "0" : date('H:i', strtotime($valor['horas_solicitadas']));

                                $fecha_permisos_desde_formateada = $valor['fecha_permisos_desde'];
                                $fecha_permiso_hasta_formateada = $valor['fecha_permiso_hasta'];
                                $fecha_permisos_desde = ($fecha_permisos_desde_formateada == '0000-00-00') ? '' : date('d/m/Y', strtotime($fecha_permisos_desde_formateada));
                                $fecha_permiso_hasta = ($fecha_permiso_hasta_formateada == '0000-00-00') ? '' : date('d/m/Y', strtotime($fecha_permiso_hasta_formateada));

                                $horas_permiso_desde = $valor['horas_permiso_desde'];
                                $horas_permiso_hasta = $valor['horas_permiso_hasta'];
                                $usuario_solicita = $valor['usuario_solicita'];
                                $usuario_aprueba = $valor['usuario_aprueba'];

                                $usuario_registra = $valor['usuario_registra'];
                                $permiso_aceptado = $valor['permiso_aceptado'];

                                if (!empty($horas_solicitadas)) {
                                    $valor_mostrar = $horas_solicitadas;
                                    $xMultiplicar = 0;
                                } elseif (!empty($dias_solicitados)) {
                                    $numeroCambio = 1.36363636363636;
                                    $valor_mostrar = $dias_solicitados;
                                    $xMultiplicar = $valor_mostrar * $numeroCambio;
                                    $xMultiplicar = substr((string)$xMultiplicar, 0, 4);
                                }
                        ?>
                        <tr>
                            <td><?=  $cedula_user ;?></td>
                            <td><?=  $nombres . " " . $apellidos ;?></td>
                            <td><?=  $fecha_permiso ;?></td>

                            <td>
                                <button class="btn btn-primary m-1" data-toggle="modal" data-target="#aprobar_soli" data-id_aprueba="<?= $id_permiso ?>" onclick="aprobar(this)">Aprobar</button>

                                <button class="btn btn-danger m-1" data-toggle="modal" data-target="#rechazar" data-id_rechazo="<?= $id_permiso ?>" onclick="rechazo(this)">Rechazar</button>

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


<!-- Modal para rechazar -->
<div class="modal fade" id="rechazar" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdminLabel">Descripcion del rechazo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="eliminarForm" action="<?php echo RUTA_ABSOLUTA ?>admin/procesarSolicitudes" method="post">
                    <div class="form-floating mb-3">
                        <div>
                            <label>Por favor Ingrese el motivo del rechazo de la Solicitud</label>
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
                <h5 class="modal-title" id="modalAdminLabel">Solicitud a aceptar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="aprobarForm" action="<?php echo RUTA_ABSOLUTA ?>admin/procesarSolicitudes" method="post">
                    <p>Estás a punto de registrar una solicitud de permiso ¿Estas Seguro de Continuar?</p>

                    <input type="hidden" name="id_aprueba" id="id_aprueba" value ="" />
                    <input class="form-control" type="hidden" name="aprobar" value ="1" />
                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese su nombre como Jefe</label>
                            <input class="form-control" name="user" type="text" required />
                        </div>
                    </div>
                    <!--
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
                    </div> -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger cerrarModal" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Aprobar</button>
                    </div>
                </form>
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
        var userId = button.getAttribute('data-id_aprueba');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('id_aprueba').value = userId;
    }
</script>
<?php include_once("../plantilla/footer.php")?>
