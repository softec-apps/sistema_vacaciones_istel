<?php
include_once "../redirection.php";
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
$titulo = "Usuarios o funcionarios";
include_once("../plantilla/header.php")
?>

<div class="container-fluid mt-5">
<?php

include_once  "funcionesCalcular.php";
foreach ($nuevosDatos2 as $valor) {
    $nombres_usuario =  $valor['nombres'];
    $apellidos_usuario = $valor['apellidos'];
    $cedula_usuario = $valor['cedula'];
    $horasOcupadasMultiplicadas = $valor['horas_ocupadas'];
    $permiso_aceptado = $valor['permiso_aceptado'];
    $tiempo_trabajo = $valor['tiempo_trabajo'];
    $motivo_rechazo = $valor['motivo_rechazo'];

}
// Obtener el mensaje de días de vacaciones y permisos
if (!empty($nuevosDatos2)) {
    // Obtener el mensaje de días de vacaciones y permisos
    $mensaje = obtenerMensajeDiasVacaciones($id, $nombreFuncionario, $apellidosFuncionario, $limiteVacaciones, $diasPorAnoTrabajado, $diasPorAno, $pdo, $tiempo_trabajo);
} else {
    // En caso de que $nuevosDatos esté vacía, asignar un valor por defecto o tomar alguna otra acción si es necesario
    $mensaje = "No Existen solicitudes Rechazadas";
}
    $nombreUser = $nombreFuncionario . " " . $apellidosFuncionario;
?>

                    <h5 class="text-gray-800"> <?php echo$mensaje; ?> </h5>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header d-flex py-3">
                            <p class="m-2 col-10 pl-0 font-weight-bold text-primary">
                                Todas las solicitudes Rechazadas
                            </p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive crud-table">
                                <table class="table table-bordered" id="tablaPermisosRechazados">
                                    <thead>
                                        <tr>
                                            <th>Tipo de permiso solicitado</th>
                                            <th>Dias Solicitadas</th>
                                            <th>Horas Solicitadas</th>
                                            <th>Cargo a vacaciones</th>
                                            <th>Motivo del Rechazo</th>
                                            <th>Solicitud</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($nuevosDatos2 as $valor) {
                                            $id_permisos =  $valor['id_permisos'];
                                            $nombres_usuario =  $valor['nombres'];
                                            $apellidos_usuario = $valor['apellidos'];
                                            $cedula_usuario = $valor['cedula'];
                                            $horasOcupadasMultiplicadas = $valor['horas_ocupadas'] ;
                                            $dias_solicitados = $valor['dias_solicitados'];
                                            $horas_solicitadas = $valor['horas_solicitadas'];
                                            $tiempo_trabajo = $valor['tiempo_trabajo'];
                                            $horas_formateadas = date('H', strtotime($horas_solicitadas));
                                            $motivo_rechazo = $valor['motivo_rechazo'];
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
                                            if ($permiso_aceptado == 2) {
                                                $permiso_aceptado = '<button class=" btn-danger" disabled title="Solicitud Rechazada" ><i class="fa-solid fa-xmark"></i></button>';
                                                $ver = null;
                                            }elseif ($permiso_aceptado == 1 || $permiso_aceptado == 3) {
                                                $permiso_aceptado = '<button class=" btn-success" disabled title="Aceptado" ><i class="fa-solid fa-check"></i></button>';
                                                $ver ='<form action="../datos_individuales" method="POST" class="d-inline-block m-1">
                                                            <input type="hidden" name="id_permisos" value="' . $id_permisos .'">
                                                            <button class="btn btn-info m-1" title="Ver solicitud aprobada">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
                                                        </form>';
                                            }elseif ($permiso_aceptado == 0) {
                                                $permiso_aceptado = '<button class=" btn-primary" disabled title="Solicitud en proceso"><i class="fa-solid fa-sync fa-spin"></i></button>';
                                                $ver = null;
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

                                            <td>
                                            <?= $motivo_rechazo?>
                                            </td>

                                            <td>
                                            <?= $permiso_aceptado?>
                                            <?= $ver?>
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
                <!-- /.container-fluid -->

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
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

</script>
<?php include_once("../plantilla/footer.php")?>

    <!-- <h1>Carpeta Usuarios o Funcionarios </h1>
    <h4>El nombre del usuario es : <?php echo $nombre ?></h4>
    <h4>La Cedula del usuario es : <?php echo $cedula ?></h4>
    <h4>El Rol del usuario es : <?php echo $rol ?></h4>
    <h4>La fecha de ingreso es  : <?php echo $fecha_ingreso ?></h4>

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