<?php
include_once "../redirection.php";
include_once "../flash_messages.php";

session_start();

if (!isset($_SESSION['id_usuarios'])) {
    redirect(RUTA_ABSOLUTA . 'logout');
}
$cedula = $_SESSION['cedula'];
$nombre = $_SESSION['nombres'];
if (empty($nombre)) {
    $nombreSesion = "Admin";
}else {
    $nombreSesion = $nombre;
}
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
$titulo = "Permisos realizados";
include_once("../plantilla/header.php")
?>

<div class="container-fluid mt-5">
<?php
include_once  "../conexion.php";
include_once  "../funciones.php";
$respuesta = soli_no_aceptadas($pdo);
?>
    <!-- Page Heading -->
    <!-- <h1 class="h3 mb-2 text-gray-800">Permisos Pendientes</h1> -->

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Solicitudes realizadas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered crud-table" id="tabla_permisos_pendientes">
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
                                $ruta_solicita = $valor['ruta_solicita'];
                                $ruta_aprueba = $valor['ruta_aprueba'];
                                $ruta_registra = $valor['ruta_registra'];

                                if (!empty($ruta_solicita) && $permiso_aceptado == 0) {
                                    $ruta_solicita = '<a class="btn btn-success m-1" title="Solicitud firmada" href="' . RUTA_ABSOLUTA . $ruta_solicita .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';
                                    $ruta_aprueba = null;

                                    $ruta_registra = null;

                                    $permiso_aceptado = null;

                                    $btnAprobar = '<button class="btn btn-primary m-1" data-toggle="modal" data-target="#aprobar_soli" data-id_aprueba="'. $id_permiso .'" onclick="aprobar(this)">Aprobar</button>';
                                    $btnRechazar = '<button class="btn btn-danger m-1" data-toggle="modal" data-target="#rechazar" data-id_rechazo="'. $id_permiso .'" onclick="rechazo(this)">Rechazar</button>';

                                    $eliminar = null;
                                }elseif ($permiso_aceptado == 1) {
                                    $ruta_solicita = '<a class="btn btn-success m-1" title="Solicitud firmada por el usuario" href="' . RUTA_ABSOLUTA . $ruta_solicita .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                    $ruta_aprueba = '<a class="btn btn-primary m-1" title="Solicitud firmada por el jefe" href="' . RUTA_ABSOLUTA . $ruta_aprueba .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

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

                                    $ruta_solicita = '<a class="btn btn-success m-1" title="Solicitud firmada por el usuario" href="' . RUTA_ABSOLUTA . $ruta_solicita .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                    $ruta_aprueba = '<a class="btn btn-primary m-1" title="Solicitud firmada por el jefe" href="' . RUTA_ABSOLUTA . $ruta_aprueba .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                    $ruta_registra = '<a class="btn btn-warning m-1" title="Solicitud registrada por talento humano" href="' . RUTA_ABSOLUTA . $ruta_registra .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                    $eliminar = null;
                                }elseif ($permiso_aceptado == 0) {
                                    $permiso_aceptado = '<button class="btn btn-primary m-1" title="Subir archivo" data-toggle="modal"
                                    data-target="#subirEscaneado" data-id="'. $id_permiso .'" onclick="subirArchivo(this)" >
                                    <i class="fa-solid fa-file-arrow-up"></i>
                                    </button>';
                                    $btnAprobar = null;
                                    $btnRechazar = null;
                                    $eliminar = '<button class="btn btn-danger m-1" title="Eliminar" data-toggle="modal"
                                    data-target="#Eliminar" data-id="'. $id_permiso .'" onclick="eliminar(this)">
                                    <i class="fas fa-trash"></i>
                                    </button>';
                                }

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

                            <td class="d-flex">

                                <?=  $permiso_aceptado ;?>
                                <?=  $btnAprobar ;?>
                                <?=  $btnRechazar ;?>

                                <form action="../datos_individuales" method="POST" class="d-inline-block">
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
                <h5 class="modal-title" id="modalAdminLabel">Descripción del rechazo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="eliminarForm" action="<?php echo RUTA_ABSOLUTA ?>admin/procesarSolicitudes" method="post">
                    <div class="form-floating mb-3">
                        <div>
                            <label>Por favor ingrese el motivo del rechazo</label>
                            <input class="form-control" type="text" name="rechazo_motivo" required/>
                            <input type="hidden" name="id_rechazo" id="id_rechazo" value ="" />
                            <input type="hidden" name="rechazo" value ="2" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="eliminarForm" class="btn btn-primary">Continuar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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
                <!-- <p>Por favor para que el permiso se pueda rechazar o aceptar se debe subir el archivo firmado</p> -->
                <form id="subirForm" action="<?php echo RUTA_ABSOLUTA ?>admin/archivosPendientes" method="post" enctype="multipart/form-data">
                    <div class="form-floating mb-3">
                        <div>
                            <label>Descripción del archivo</label>
                            <textarea class="form-control" name="archivoDescripcion" id="archivoDescripcion" cols="30" rows="2" required></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="id_permiso" id="id_permisoSubir" value="">
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
                <form id="aprobarForm" action="<?php echo RUTA_ABSOLUTA ?>admin/procesarSolicitudes" method="post"  enctype="multipart/form-data">

                    <p>¿Está seguro de que desea aprobar esta solicitud ?</p>

                    <input type="hidden" name="id_aprueba" id="id_aprueba" value ="" />
                    <input type="hidden" name="id_user" id="id_user" value ="" />
                    <input class="form-control" type="hidden" name="aprobar" value ="1" />
                    <div class="form-floating mb-3">
                        <div>
                            <select class=" selectSoli" name="user" required style="width:100%;">
                                <option value="" disabled selected>Seleccione al usuario que aprueba este permiso</option>
                                <?php
                                    $iteroSinAdmin = sinAdmin($pdo);

                                    foreach ($iteroSinAdmin as $key => $posicionSin):
                                    $id_iterado = $posicionSin ["id_usuarios"];
                                    $cedula_iterada = $posicionSin ["cedula"];
                                    $nombres_iterado = $posicionSin ["nombres"];
                                    $apellidos_iterado = $posicionSin ["apellidos"];
                                    $nombreCompleto = $nombres_iterado . " " . $apellidos_iterado;
                                ?>
                                <option value="<?php echo $nombreCompleto;?>" data-id="<?php echo $id_iterado;?>"><?php echo $nombreCompleto;?></option>

                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <div>
                            <label>Descripción del archivo</label>
                            <textarea class="form-control" name="archivoDescripcion" id="archivoDescripcion" cols="30" rows="2" required></textarea>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Subir el permiso firmado</label>
                            <input class="form-control" type="file" name="archivoAprueba" id="archivoAprueba" required>
                        </div>
                    </div>

                    <input type="hidden" name="asistirAprobacion" value="<?= $nombreSesion?>">

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Aprobar</button>
                        <button type="button" class="btn btn-danger cerrarModal" data-dismiss="modal">Cancelar</button>
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

    $('.selectSoli').select2({
        dropdownParent: $('#aprobar_soli .modal-body')
    });

    function subirArchivo(button) {
        var permisoId = button.getAttribute('data-id');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('id_permisoSubir').value = permisoId;
    }

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

    $(document).ready(function(){
        // Cuando cambia la selección en el campo 'user'
        $('select[name="user"]').change(function(){
            // Obtener directamente el valor del atributo 'value' de la opción seleccionada (nombre)
            var nombreCompleto = $(this).val();

            // Obtener el valor del atributo 'data-id' de la opción seleccionada (id_iterado)
            var idIterado = $(':selected', this).data('id');

            // Actualizar el valor de 'id_user' con el id_iterado correspondiente
            $('#id_user').val(idIterado);
            console.log(idIterado);
        });
    });
</script>
<?php include_once("../plantilla/footer.php")?>
