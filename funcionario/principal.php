<?php
include_once "../redirection.php";
include_once "../flash_messages.php";
 session_start();

 if (!isset($_SESSION['id_usuarios'])) {
    redirect("inicio");
 }
$id = $_SESSION['id_usuarios'];
$cedula = $_SESSION['cedula'];
$nombreFuncionario = $_SESSION['nombres'];
$apellidosFuncionario = $_SESSION['apellidos'];
$rol = $_SESSION['rol'];
$fecha_ingreso = $_SESSION['fecha_ingreso'];

if ($rol != 'Funcionario') {
    redirect("inicio");
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
include_once  "../resta_solicitud.php";
$nombreUser = $nombreFuncionario . " " . $apellidosFuncionario;
?>
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Funcionario <?= $nombreUser?></h1>

<div class="row">

<?php
    $resultados_usuario = obtenerDiasTrabajadosParaUsuario($pdo,$id);

    $usuario = $resultados_usuario[0];

    $id_usuario = $usuario['id_usuario'];
    $cedulaIterada = $usuario['cedula'];
    $nombresIterado = $usuario['nombre'];
    $apellidosIterado = $usuario['apellido'];
    $diasTrabajados = $usuario['dias_trabajados'];
    $horasDePermisoSolicitadas = $usuario['horas_permiso'];
    $fechaIngreso = $usuario['fecha_ingreso'];
    $tiempoTrabajo = $usuario['tiempo_trabajo'];

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
    if ($tiempoTrabajo == 8) {
        $tiempoTrabajo = "Tiempo Completo";
    }elseif ($tiempoTrabajo == 4) {
        $tiempoTrabajo = "Medio Tiempo";
    }else {
        $tiempoTrabajo = 0;
    }
?>
<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Dias de vacaciones</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $diasDeVacaciones  ?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-umbrella-beach fa-2x text-gray-300"></i>
                    <!-- <i class="fas fa-calendar fa-2x text-gray-300"></i> -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Dias de vacaciones Utilizados</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $diasDePermisoSolicitados  ?></div>
                </div>
                <div class="col-auto">
                    <!-- <i class="fas fa-dollar-sign "></i> -->
                    <i class="far fa-calendar-check fa-2x text-gray-300"></i>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Porcentaje de dias Utilizados</div>
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                            <?php
                                // Calcular el porcentaje de días de permiso en relación con los días limite
                                $porcentaje = round(($diasDePermisoSolicitados / $limiteVacaciones) * 100);
                            ?>
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $porcentaje ?>%</div>
                        </div>
                        <div class="col">
                            <div class="progress progress-sm mr-2">
                                <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $porcentaje ?>%" aria-valuenow="<?php echo $porcentaje ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="bi bi-percent fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Requests Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Dias Trabajados</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $diasTrabajados?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    Historal de dias totales acumulados</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $dias_totales  ?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-history fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    Trabajo</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $tiempoTrabajo  ?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal para solicitar permisos -->
<div class="modal fade" id="registrar_vacaciones" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdminLabel">Solicitar Permiso </h5>
                <button type="button" class="close cerrarModal" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../solicitar_permiso" method="post">
                    <input type="hidden" name="nombre_usuario" value="<?= $nombreUser?>">
                    <input type="hidden" name="id_usuario" value="<?php echo $id; ?>">
                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese la provincia </label>
                            <input class="form-control" type="text" name="provincia"  />
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese el Regimen </label>
                            <input class="form-control" type="text" name="regimen"  />
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese la coordinacion Zonal </label>
                            <input class="form-control" type="text" name="coordinacion"  />
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese la Dirrecion o Unidad </label>
                            <input class="form-control" type="text" name="direccion"  />
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese la fecha inicio del permisos</label>
                            <input class="form-control" type="date" name="fecha_inicio"  />
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese la fecha fin del permisos</label>
                            <input class="form-control" type="date" name="fecha_fin"  />
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese la hora inicio del permisos(opcional)</label>
                            <input class="form-control" type="time" name="hora_inicio"  />
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese la hora fin del permisos(opcional)</label>
                            <input class="form-control" type="time" name="hora_fin"  />
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                            <label>Seleccione el motivo del permisos</label>
                            <select class="form-control" name="motivo">
                                <option value="LICENCIA_POR_CALAMIDAD_DOMESTICA">Licencia por calamidad domestica</option>
                                <option value="LICENCIA_POR_ENFERMEDAD">Licencia por enfermedad</option>
                                <option value="LICENCIA_POR_MATERNIDAD">Licencia por maternidad</option>
                                <option value="LICENCIA_POR_MATRIMONIO_O_UNION_DE_ECHO">Licencia por matrimonio o union de echo</option>
                                <option value="LICENCIA_POR_PATERNIDAD">Licencia por paternidad</option>
                                <option value="PERMISO_PARA_ESTUDIOS_REGULARES">Permiso pra estudios regulares</option>
                                <option value="PERMISO_DE_DIAS_CON_CARGO_A_VACACIONES">Permisos de dias con cargo a vacaciones</option>
                                <option value="PERMISO_POR_ASUNTOS_OFICIALES">Permiso por asuntos oficales</option>
                                <option value="PERMISO_PARA_ATENCION_MEDICA">Permiso para atencion medica</option>
                                <option value="OTROS">otros</option>
                            </select>
                        </div>
                    </div>

                    <input class="form-control" type="hidden" name="permiso_aceptado" value="0" />

                    <div class="form-floating mb-3">
                        <div>
                            <label>Ingrese observaciones o justificativos del permisos(opcional)</label>
                            <input class="form-control" type="text" name="observaciones"  />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger cerrarModal" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Solicitar Permiso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(".cerrarModal").click(function(){
        $("#registrar_vacaciones").modal('hide');
    });

</script>
<?php include_once("../plantilla/footer.php")?>

    <!-- <h1>Carpeta Usuarios o Funcionarios

    <div class="container-fluid">
    <div class="col-12 px-5">
        <div class="d-flex">
            <p class="m-2 col-10 pl-0 font-weight-bold text-primary">Estadisticas de Trabajo</p>
        </div>

        <div class="canvas shadow m-2">
            <div class=" canvas ">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 px-5">
        <div class="d-flex">
            <p class="m-2 col-10 pl-0 font-weight-bold text-primary">Estadisticas de Vacaciones</p>
        </div>
        <div class="canvas shadow m-2 ">
            <div class="canvas">
                <canvas id="Newchart"></canvas>
            </div>
        </div>
    </div>
</div> -->

<!-- <script>
var labels = [];

const data = {
    labels: labels,
    datasets: [
        {
            label: 'Trabajo',
            data: [],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192)',
            fill: false,
        },
    ],
};

const config = {
    type: 'bar',
    data: data,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Estadísticas de Trabajo',
            },
        },
        interaction: {
            intersect: false,
        },
        scales: {
            x: {
                display: true,
                title: {
                    display: true,
                    text: 'X trabajo',
                },
            },
            y: {
                display: true,
                title: {
                    display: true,
                    text: 'y trabajo',
                },
                suggestedMin: 0,
                suggestedMax: 100,
            },
        },
    },
};

const Newdata = {
    labels: [],
    datasets: [
        {
            label: 'Vacaciones',
            data: [],
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132)',
            fill: false,
        },
    ],
};

const Newconfig = {
    type: 'bar',
    data: Newdata,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Estadísticas de Vacaciones',
            },
        },
        interaction: {
            intersect: false,
        },
        scales: {
            x: {
                display: true,
                title: {
                    display: true,
                    text: 'X Vacaciones',
                },
            },
            y: {
                display: true,
                title: {
                    display: true,
                    text: 'Y Vacaciones',
                },
                suggestedMin: 0,
                suggestedMax: 10,
            },
        },
    },
};

var ctx = document.getElementById("myChart").getContext("2d");
var myChart = new Chart(ctx, config);

var newChart = document.getElementById("Newchart").getContext("2d");
var NewChart = new Chart(newChart, Newconfig);

</script> -->


<?php include_once("../plantilla/footer.php")?>