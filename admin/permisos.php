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

$titulo = "Registrar permisos";
include_once("../plantilla/header.php")
?>
<?php
include_once  "../conexion.php";
include_once  "../funciones.php";
$respuesta = permisosAprobados($pdo);
?>
                <div class="container-fluid mt-5">
                    <!-- Page Heading -->
                    <!-- <h1 class="h3 mb-2 text-gray-800">Permisos Aprobados por el supervisor</h1> -->

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Permisos listos para registrar</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive crud-table">
                                <table class="table table-bordered" id="tabla_permisos">
                                    <thead>
                                        <tr>
                                            <th>Cédula </th>
                                            <th>Funcionario</th>
                                            <th>Fecha emitida</th>
                                            <th>Tipo permiso</th>
                                            <th>Registrar solicitud</th>
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
                                                <button class="btn btn-success m-1" data-toggle="modal" data-target="#registrar_solicitud" data-registrar="<?= $id_permiso ?>" onclick="aprobar(this)">Registrar</button>
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
                <!-- /.container-fluid -->

<!-- Modal de registrar la solicitud-->
<div class="modal fade" id="registrar_solicitud" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdminLabel">Registrar solicitud </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="aprobarForm" action="<?php echo RUTA_ABSOLUTA ?>procesar" method="post"  enctype="multipart/form-data">
                    <p>Estás a punto de registrar una solicitud ¿Deseas continuar? </p>

                    <input type="hidden" name="id_registrar" id="id_registrar" value ="" />
                    <input type="hidden" name="registrar" value ="3" />
                    <input type="hidden" name="id_user" id="id_user" value ="" />

                    <div class="mb-3">
                        <select class=" selectSoli" name="user"  required style="width:100%;">
                            <option value="" disabled selected>Seleccione al usuario que registra la solicitud</option>
                            <?php
                                $iteroSinAdmin = sinAdmin($pdo);

                                foreach ($iteroSinAdmin as $key => $posicionSin):
                                $id_iterado = $posicionSin ["id_usuarios"];
                                $cedula_iterada = $posicionSin ["cedula"];
                                $nombres_iterado = $posicionSin ["nombres"];
                                $apellidos_iterado = $posicionSin ["apellidos"];
                                $nombreCompleto = $nombres_iterado . " " . $apellidos_iterado;
                            ?>
                            <option value="<?php echo $nombreCompleto;?>" data-id="<?= $id_iterado?>"><?php echo $nombreCompleto;?></option>

                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Archivo firmado por la persona que registra</label>
                            <input class="form-control" type="file" name="archivoRegistra" id="archivoRegistra" required>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Descripción del archivo</label>
                            <textarea class="form-control" name="archivoDescripcion" id="archivoDescripcion" cols="30" rows="2" required></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="asisteRegistra" value="<?= $nombreSesion?>">

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Registrar solicitud</button>
                        <button type="button" class="btn btn-secondary cerrarModal" data-dismiss="modal">Cancelar</button>
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
        dropdownParent: $('#registrar_solicitud .modal-body')
    });
    function aprobar(button) {
        var userId = button.getAttribute('data-registrar');
        // Rellenar el campo oculto con el ID del cliente
        document.getElementById('id_registrar').value = userId;
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
