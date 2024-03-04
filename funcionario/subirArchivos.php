<?php
include_once "../redirection.php";
include_once "../flash_messages.php";
 session_start();

 if (!isset($_SESSION['id_usuarios'])) {

    redirect(RUTA_ABSOLUTA . "logout");
 }
$id = $_SESSION['id_usuarios'];
$cedula = $_SESSION['cedula'];
$nombreFuncionario = $_SESSION['nombres'];
$apellidosFuncionario = $_SESSION['apellidos'];
$rol = $_SESSION['rol'];
$fecha_ingreso = $_SESSION['fecha_ingreso'];

if ($rol != 'Funcionario') {

    redirect(RUTA_ABSOLUTA . "logout");
}

$message = '';
$type = '';
$flash_message = display_flash_message();

if (isset($flash_message)) {
    $message = $flash_message['message'];
    $type = $flash_message['type'];
}

$titulo = "Usuarios o funcionarios";
include_once("../plantilla/header.php")
?>

<div class="container-fluid mt-5">
<?php

?>
<?php

include_once  "funcionesCalcular.php";
$subirArchivos = SubirArchivos($id,$pdo);
foreach ($subirArchivos as $valor) {
    $nombres_usuario =  $valor['nombres'];
    $apellidos_usuario = $valor['apellidos'];
    $cedula_usuario = $valor['cedula'];
    $horasOcupadasMultiplicadas = $valor['horas_ocupadas'];
    $permiso_aceptado = $valor['permiso_aceptado'];
    $tiempo_trabajo = $valor['tiempo_trabajo'];

}

?>

    <h5 class="text-gray-800">Archivos de los permiso realizados</h5>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header d-flex py-3">
            <p class="m-2 col-10 pl-0 font-weight-bold text-primary">
                Subir el archivo para que el permiso pueda ser aprobado o rechazado
            </p>
        </div>
        <div class="card-body">
            <div class="table-responsive crud-table">
                <table class="table table-bordered" id="tablaSubirArchivos">
                    <thead>
                        <tr>
                            <th>Tipo de permiso solicitado</th>
                            <th>Días solicitados</th>
                            <th>Horas solicitadas</th>
                            <th>Descuento de días de vacaciones</th>
                            <th class="exclude">Solicitud</th>
                            <th class="exclude">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($subirArchivos as $valor) {
                            $id_permisos =  $valor['id_permisos'];
                            $nombres_usuario =  $valor['nombres'];
                            $apellidos_usuario = $valor['apellidos'];
                            $cedula_usuario = $valor['cedula'];
                            $horasOcupadasMultiplicadas = $valor['horas_ocupadas'] ;
                            $dias_solicitados = $valor['dias_solicitados'];
                            $horas_solicitadas = $valor['horas_solicitadas'];
                            $tiempo_trabajo = $valor['tiempo_trabajo'];
                            $horas_formateadas = date('H', strtotime($horas_solicitadas));
                            $motivo_permiso = $valor['motivo_permiso'];
                            $motivo_permiso = str_replace('_', ' ', $motivo_permiso);


                            if ($horasOcupadasMultiplicadas >= 8) {
                                $diasSolicitados = $horasOcupadasMultiplicadas / $tiempo_trabajo;
                                $horasOcupadasMultiplicadas = 0;
                                $tipo_solicitud = "Si";
                            }elseif ($horasOcupadasMultiplicadas == 0) {
                                $diasSolicitados = $dias_solicitados;
                                $horasOcupadasMultiplicadas = $horas_formateadas;
                                $tipo_solicitud = "No";
                            }elseif($horasOcupadasMultiplicadas < 8) {
                                $diasSolicitados = 0;
                                $horasOcupadasMultiplicadas = $horasOcupadasMultiplicadas;
                                $tipo_solicitud = "Si";
                            }
                            $permiso_aceptado = $valor['permiso_aceptado'];
                            $ruta_solicita = $valor ["ruta_solicita"];
                            $ruta_aprueba = $valor['ruta_aprueba'];
                            $ruta_registra = $valor['ruta_registra'];

                            if (!empty($ruta_solicita) && $permiso_aceptado == 0) {
                                $ruta_solicita = '<a class="btn btn-warning m-1" title="Solicitud firmada por el funcionario" href="' . RUTA_ABSOLUTA . $ruta_solicita .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';
                                $ruta_aprueba = null;

                                $ruta_registra = null;
                                $permisoAceptadoClass ="bg-primary text-white text-center";
                                $title = "Solicitud en proceso";
                                $icono = '<i class="fa-lg fa-solid fa-sync fa-spin mt-3"></i>';

                                $archivo = null;
                                $eliminar = null;
                            }elseif ($permiso_aceptado == 2) {

                                $ruta_solicita = '<a class="btn btn-warning m-1" title="Solicitud firmada por el funcionario" href="' . RUTA_ABSOLUTA . $ruta_solicita .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';
                                $ruta_aprueba = null;
                                $ruta_registra = null;

                                $permisoAceptadoClass ="bg-danger text-white text-center";
                                $title = "Solicitud rechazada";
                                $icono = '<i class="fa-lg fa-solid fa-xmark mt-3"></i>';
                                $archivo = null;
                                $eliminar = null;
                            }elseif ($permiso_aceptado == 1) {

                                $ruta_solicita = '<a class="btn btn-warning m-1" title="Solicitud firmada por el funcionario" href="' . RUTA_ABSOLUTA . $ruta_solicita .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                $ruta_aprueba = '<a class="btn btn-success m-1" title="Solicitud firmada por el jefe" href="' . RUTA_ABSOLUTA . $ruta_aprueba .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                $ruta_registra = null;

                                $icono = '<i class="fa-lg fa-solid fa-check mt-3"></i>';
                                $permisoAceptadoClass ="bg-success text-white text-center";
                                $title = "Solicitud aceptada";

                                $archivo = null;

                                $eliminar = null;
                            }elseif ($permiso_aceptado == 3) {
                                $icono = '<i class="fa-lg fa-solid fa-check mt-3"></i>';
                                $permisoAceptadoClass ="bg-success text-white text-center";
                                $title = "Solicitud aceptada";

                                $ruta_solicita = '<a class="btn btn-warning m-1" title="Solicitud firmada por el funcionario" href="' . RUTA_ABSOLUTA . $ruta_solicita .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                $ruta_aprueba = '<a class="btn btn-success m-1" title="Solicitud firmada por el jefe" href="' . RUTA_ABSOLUTA . $ruta_aprueba .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                $ruta_registra = '<a class="btn btn-dark m-1" title="Solicitud registrada por talento humano" href="' . RUTA_ABSOLUTA . $ruta_registra .'" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';

                                $archivo = null;

                                $eliminar = null;
                            }elseif ($permiso_aceptado == 0) {
                                $permisoAceptadoClass ="bg-primary text-white text-center";
                                $title = "Solicitud en proceso";
                                $icono = '<button class="btn btn-primary m-1" title="Subir archivo" data-toggle="modal"
                                data-target="#subirEscaneado" data-id="'. $id_permisos .'" onclick="subirArchivo(this)" >
                                <i class="fa-lg fa-solid fa-file-lines"></i>
                                </button>';

                                $archivo =  null;

                                $eliminar = '<button class="btn btn-danger m-1" title="Eliminar" data-toggle="modal"
                                data-target="#Eliminar" data-id="'. $id_permisos .'" onclick="eliminar(this)">
                                <i class="fas fa-trash"></i>
                                </button>';
                            }

                        ?>
                        <tr>
                            <td>
                                <?= $motivo_permiso?>
                            </td>

                            <td>
                            <?= $diasSolicitados?>
                            </td>

                            <td>
                            <?= $horasOcupadasMultiplicadas?>
                            </td>

                            <td>
                            <?= $tipo_solicitud?>
                            </td>

                            <td class="<?= $permisoAceptadoClass?>" title="<?= $title; ?>">
                                <?= $icono?>
                            </td>

                            <td class="d-flex">
                                <form action="../datos_individuales" method="POST" class="d-inline-block m-1">
                                    <input type="hidden" name="id_permisos" value="<?= $id_permisos?> ">
                                    <button class="btn btn-info m-1" title="Ver solicitud aprobada">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </form>
                            <?= $ruta_solicita?>
                            <?= $ruta_aprueba?>
                            <?= $ruta_registra?>
                            <?= $archivo?>
                            <?= $eliminar?>
                            </td>


                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

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
                <p>¿Está seguro de que desea eliminar este registro de un permiso?</p>
                <form id="eliminarForm" action="<?php echo RUTA_ABSOLUTA ?>funcionario/eliminar" method="post">
                    <input type="hidden" name="id_permiso" id="id_permiso" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="eliminarForm" class="btn btn-danger">Eliminar</button>
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

                <form id="subirForm" action="<?php echo RUTA_ABSOLUTA ?>funcionario/procesarSubida" method="post" enctype="multipart/form-data">
                    <div class="form-floating mb-3">
                        <div>
                            <label>Descripcion del archivo</label>
                            <textarea class="form-control" name="archivoDescripcion" id="archivoDescripcion" cols="30" rows="2" required></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="id_permiso" id="id_permisoSubir" value="">
                    <label>Subir archivo firmado </label>
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
        $("#registrar_vacaciones").modal('hide');
    });
    function eliminar(button) {
        var userId = button.getAttribute('data-id');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('id_permiso').value = userId;
    }
    function subirArchivo(button) {
        var permisoId = button.getAttribute('data-id');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('id_permisoSubir').value = permisoId;
    }
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

</script>
<?php include_once("../plantilla/footer.php")?>