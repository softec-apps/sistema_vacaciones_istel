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
include_once "../funciones.php";
?>

                <div class="container-fluid mt-5">

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Días de vacaciones y días trabajados de los funcionarios</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered crud-table" id="tabla_vacaciones">
                                    <thead>
                                        <tr>
                                            <th>N° Cédula</th>
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
                                            $porcentajeVerde = 50;
                                            $porcentajeAmarillo = 75;

                                            $limiteVerde = $limiteVacaciones*($porcentajeVerde/100);
                                            $limiteAmarillo = $limiteVacaciones*($porcentajeAmarillo/100);
                                            $limiteRojo = $limiteVacaciones;
                                            $horasTrabajadas = $diasTrabajados * $tiempoTrabajo;
                                            $prueba = calcular_actualizar($pdo,$diasTrabajados,$horasTrabajadas,$dias_totales,$diasDeVacaciones,$id_usuario);
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
                                                if ($diasDeVacaciones < 0) {
                                                    echo 'class="bg-dark rounded-4 text-white" title="El usuario posee días negativos"';
                                                }elseif ($diasDeVacaciones == $limiteVerde || $diasDeVacaciones < $limiteAmarillo) {
                                                    echo 'class="bg-success rounded-4 text-white" title="Dias disponibles en orden"';
                                                } elseif ($diasDeVacaciones == $limiteAmarillo || $diasDeVacaciones < $limiteRojo) {
                                                    echo 'class="bg-warning rounded-4" title="Se acerca al limite"';
                                                } elseif ($diasDeVacaciones >= $limiteRojo) {
                                                    echo 'class="bg-danger text-white rounded-4" title="El limite es ' . $limiteRojo .' dias"';
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
