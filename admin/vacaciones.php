<?php
include_once "../redirection.php";
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

$titulo = "Vacaciones";
include_once("../plantilla/header.php");

include_once("../resta_solicitud.php");
?>

                <div class="container-fluid mt-5">

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Días de Vacaciones y días trabajados de los funcionarios</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered crud-table" id="tabla_vacaciones">
                                    <thead>
                                        <tr>
                                            <th>Cédula</th>
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
                                            $diasDePermisoSolicitados = $horasDePermisoSolicitadas / $tiempoTrabajo;

                                            $dias_totales = $diasDeVacaciones + $diasDePermisoSolicitados;
                                            $limiteVerde = 30;
                                            $limiteAmarillo = 45;
                                            $limiteRojo = 60;
                                    ?>
                                        <tr>

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
                                                    echo 'class="bg-success text-white" title="Dias disponibles en orden"';
                                                } elseif ($diasDeVacaciones == $limiteAmarillo || $diasDeVacaciones < $limiteRojo) {
                                                    echo 'class="bg-warning" title="Se acerca al limite"';
                                                } elseif ($diasDeVacaciones >= $limiteRojo) {
                                                    echo 'class="bg-danger text-white" title="El limite es 60 dias"';
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

<script>
    $(".cerrarModal").click(function(){
  $("#registrar_vacaciones").modal('hide')
});
</script>
<?php include_once("../plantilla/footer.php")?>
