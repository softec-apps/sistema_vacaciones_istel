<?php
include_once "../redirection.php";
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

$titulo = "DiasTrabajoVacaciones";
include_once("../plantilla/header.php");

include_once("../resta_solicitud.php");
?>

                <div class="container-fluid mt-5">

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Dias de Vacaciones y dias trabajados de los funcionarios</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered crud-table" id="tabla_vacaciones">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Cedula</th>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>D.T</th>
                                            <th>D.D.VAC</th>
                                            <th>D.OCP</th>
                                            <th>H.T.A</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        // Ejemplo de uso
                                        $resultadosTodosUsuarios = obtenerDiasTrabajadosParaTodos($pdo);

                                        foreach ($resultadosTodosUsuarios as $key => $resultado) {
                                            $id_usuario = $resultado['id_usuario'];
                                            $cedulaIterada = $resultado['cedula'];
                                            $nombresIterado = $resultado['nombre'];
                                            $apellidosIterado = $resultado['apellido'];
                                            $diasTrabajados = $resultado['dias_trabajados'];
                                            $horasDePermisoSolicitadas = $resultado['horas_permiso'];
                                            $fechaIngreso = $resultado['fecha_ingreso'];
                                            $tiempoTrabajo = $resultado['tiempo_trabajo'];

                                            $diasDeVacaciones = calcularDiasVacaciones(
                                                $diasTrabajados,
                                                $horasDePermisoSolicitadas,
                                                $limiteVacaciones,
                                                $diasPorAnoTrabajado,
                                                $diasPorAno,
                                                $tiempoTrabajo
                                            );
                                            // if ($diasDeVacaciones > $limiteVacaciones) {
                                            //     echo "Error: El total de días de vacaciones supera el límite permitido de $limiteVacaciones días.";
                                            // } else {
                                                $diasDePermisoSolicitados = $horasDePermisoSolicitadas / $tiempoTrabajo;
                                                /* echo "El empleado tiene derecho a $diasDeVacaciones días de vacaciones. Se han utilizado $diasDePermisoSolicitados días de permiso.";
                                                echo"<br/>"; */
                                            // }
                                            $dias_totales = $diasDeVacaciones + $diasDePermisoSolicitados;
                                            $limiteVerde = 30;
                                            $limiteAmarillo = 45;
                                            $limiteRojo = 60;
                                    ?>
                                        <tr>
                                            <td>
                                            <?= $key + 1 ?>
                                            </td>

                                            <td>
                                            <?= $cedulaIterada ?>
                                            </td>

                                            <td>
                                            <?= $nombresIterado ?>
                                            </td>

                                            <td>
                                            <?= $apellidosIterado ?>
                                            </td>

                                            <td>
                                            <?= $diasTrabajados ?>
                                            </td>

                                            <td <?php
                                                if ($diasDeVacaciones == $limiteVerde || $diasDeVacaciones < $limiteAmarillo) {
                                                    echo 'class="bg-success rounded-4 text-white" title="Dias disponibles en orden"';
                                                } elseif ($diasDeVacaciones == $limiteAmarillo || $diasDeVacaciones < $limiteRojo) {
                                                    echo 'class="bg-warning rounded-4" title="Se acerca al limite"';
                                                } elseif ($diasDeVacaciones >= $limiteRojo) {
                                                    echo 'class="bg-danger text-white rounded-4" title="El limite es 60 dias"';
                                                }
                                                ?>>
                                                <?php echo $diasDeVacaciones; ?>
                                            </td>

                                            <td>
                                            <?= $diasDePermisoSolicitados ?>
                                            </td>

                                            <td>
                                            <?= $dias_totales ?>
                                            </td>

                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->




    <!-- Modal Editar datos del usuario -->
    <!-- <div class="modal fade" id="Editar_datos" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdminLabel">Editar datos del usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="editar_datos" method="post">
                        <input type="hidden" name="cliente_id" value="">
                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese el nuevo nombre de usuario</label>
                                <input class="form-control" name="user" type="text" required />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese el nuevo Email del Usuario</label>
                                <input class="form-control" type="email" name="email" required />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Actualizar Informacion</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> -->



    <!-- Modal para registrar administradores -->
    <!-- <div class="modal fade" id="registrar_vacaciones" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdminLabel">Coloque los dias limites </h5>
                    <button type="button" class="close cerrarModal" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="editar_datos" method="post">
                        <input type="hidden" name="cliente_id" value="">
                        <div class="form-floating mb-3">
                            <div>
                                <label>Dias limite por dias acumulados</label>
                                <input class="form-control" name="user" type="text" required />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <div>
                                <label>Ingrese el nuevo Email del Usuario</label>
                                <input class="form-control" type="email" name="email" required />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger cerrarModal" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success">Registrar dias limite</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> -->

<script>
    $(".cerrarModal").click(function(){
  $("#registrar_vacaciones").modal('hide')
});
</script>
<?php include_once("../plantilla/footer.php")?>
