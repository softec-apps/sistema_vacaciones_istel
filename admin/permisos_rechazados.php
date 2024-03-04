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
                            <h6 class="m-0 font-weight-bold text-primary">Permisos Rechazados</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive crud-table">
                                <table class="table table-bordered" id="tabla_permisos_aceptados">
                                    <thead>
                                        <tr>
                                            <th>Cédula </th>
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
                                                $provincia = $valor ["provincia"];
                                                $regimen = $valor ["regimen"];
                                                $coordinacion_zonal = $valor ["coordinacion_zonal"];
                                                $direccion_unidad = $valor ["direccion_unidad"];
                                                $fecha_permiso = $valor ["fecha_permiso"];
                                                $motivo_permiso = $valor ["motivo_permiso"];
                                                $motivo_permiso = str_replace('_', ' ', $motivo_permiso);
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
                <form id="AceptarS" action="<?php echo RUTA_ABSOLUTA ?>admin/procesarSolicitudes" method="POST">

                    <p>¿Está seguro de que desea cancelar el permiso rechazado?</p>
                    <input type="hidden" name="id_aprueba" id="id_aprueba" value ="" />
                    <input class="form-control" type="hidden" name="aprobarRechazo" value ="0" />
                    <input class="form-control" type="hidden" name="aprobar_rechazo" value ="" />

                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="AceptarS" class="btn btn-primary">Continuar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(".cerrarModal").click(function(){
        $("#registrar_permisos").modal('hide')
    });
    $('.selectSoli').select2({
        dropdownParent: $('#aceptarSolicitud .modal-body')
    });
    function aceptar(button) {
        var userId = button.getAttribute('data-id');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('id_aprueba').value = userId;
    }
</script>
<?php include_once("../plantilla/footer.php")?>
