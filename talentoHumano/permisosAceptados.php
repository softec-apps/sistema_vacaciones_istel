<?php
include_once "../redirection.php";
session_start();

if (!isset($_SESSION['id_usuarios'])) {
    redirect(RUTA_ABSOLUTA . 'logout');
}
$cedulaTalentoHumano = $_SESSION['cedula'];
$nombreTalentoHumano = $_SESSION['nombres'];
$apellidoTalentoHumano = $_SESSION['apellidos'];
$rol = $_SESSION['rol'];

if ($rol != ROL_TALENTO_HUMANO) {
   redirect(RUTA_ABSOLUTA . "logout");
}

$titulo = "Permisos Aprobados J.S";
include_once("../plantilla/header.php")
?>
<?php
include_once  "../conexion.php";
include_once  "../funciones.php";
$respuesta = permisosAprobados($pdo);
?>
                <div class="container-fluid mt-5">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Permisos Aprobados por el supervisor</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Permisos Aprobados</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive crud-table">
                                <table class="table table-bordered" id="tabla_permisos">
                                    <thead>
                                        <tr>
                                            <th>Cedula</th>
                                            <th>Funcionario</th>
                                            <th>Fecha emitida</th>
                                            <th>Tipo permiso</th>
                                            <th>Datos Solicitud</th>
                                            <th>Registrar Solicitud</th>
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
                                            <td><?=  $motivo_permiso ;?></td>
                                            <td>
                                                <form action="../datos_individuales" method="POST" class="d-inline-block m-1">
                                                    <input type="hidden" name="id_permisos" value=" <?= $id_permiso ?>">
                                                    <button class="btn btn-info m-1" title="Ver los datos de esta solicitud">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </form>
                                            </td>

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
                    <form id="eliminarForm" action="eliminar_admin" method="post">
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




    <!-- Modal Editar Pemisos-->
    <div class="modal fade" id="Editar_permisos" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdminLabel">Editar Permisos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="actualizar_permisos" method="POST">
                        <input type="hidden" name="id_administrador" id="id_administrador" value="">

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="permisos[]" value="Administracion"
                                id="permiso_administracion">
                            <label class="form-check-label" for="permiso_administracion">Administración</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="permisos[]" value="Bolsa de empleo"
                                id="permiso_bolsa">
                            <label class="form-check-label" for="permiso_bolsa">Bolsa de Empleo</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="permisos[]"
                                value="Seguimiento graduados" id="permiso_seguimiento">
                            <label class="form-check-label" for="permiso_seguimiento">Seguimiento Graduados</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="permisos[]"
                                value="Eventos y Encuestas" id="eventos_encuestas">
                            <label class="form-check-label">Eventos y Encuestas</label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Actualizar los Permisos</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


<!-- Modal de aprobar la solicitud-->
<div class="modal fade" id="registrar_solicitud" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdminLabel">Registrar Solicitud de Permiso </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="aprobarForm" action="<?php echo RUTA_ABSOLUTA ?>procesar" method="post">
                    <p>Estás a punto de registrar una solicitud de permiso</p>

                    <input type="hidden" name="id_registrar" id="id_registrar" value ="" />
                    <input class="form-control" type="hidden" name="registrar" value ="3" />
                    <div class="form-floating mb-3">
                        <div>
                            <!-- <label>Ingrese su nombre T.H</label> -->
                            <input class="form-control" name="user" type="hidden" value="<?= $nombreTalentoHumano . " " . $apellidoTalentoHumano?>" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary cerrarModal" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Registrar Solicitud de permiso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



    <!-- Modal para registrar nuevos permisos -->
    <div class="modal fade" id="registrar_permisos" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdminLabel">Registrar solicitudes de permisos</h5>
                    <button type="button" class="close cerrarModal" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Selecciona "Registrar" si deseeas registrar todas las solicitudes de todos los funcionarios</p>
                    <form id="eliminarForm" action="eliminar_admin" method="post">
                        <input type="hidden" name="cliente_id" id="cliente_id" value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cerrarModal" data-dismiss="modal">Cancelar</button>
                    <button type="submit" form="eliminarForm" class="btn btn-primary">Registrar</button>
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
</script>
<?php include_once("../plantilla/footer.php")?>
