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

$titulo = "Panel";
include_once "../plantilla/header.php";

include_once "estadisticas.php";
$estadisticas = total_usuarios($pdo);
$estadisticas = $estadisticas[0]["total"];
$permisoAceptados = countAceptados($pdo);
$permisoAceptados = $permisoAceptados[0]["total"];
$permisoRegistrados = countRegistrados($pdo);
$permisoRegistrados = $permisoRegistrados[0]["total"];
$permisoRechazados = permisosRechazados($pdo);
$permisoRechazados = $permisoRechazados[0]["total"];
$permisosRealizados = permisosRealizados($pdo);
$permisosRealizados = $permisosRealizados[0]["total"];
?><style>
        .card-body .hoverable .fa-2x:hover  {
            color:  #001f3f;
        }
        .fa-2x{
            color: #fff;
        }
    </style>
<div class="container-fluid mt-4">
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Inicio</h1>
    <!-- <div class="w-25 text-end">
        <img class=" d-none d-sm-inline-block w-25" src="<?php echo RUTA_ABSOLUTA; ?>imagenes_defecto/logo_instituto.png">
    </div> -->
</div>



    <div class="row ">
        <!-- Carta 1 -->
        <div class="col-xl-3 col-md-6 mb-4 " >
            <div class="card shadow h-100 py-2 bg-primary rounded-5">
                <div class="card-body ">
                    <div class="row no-gutters align-items-center ">
                        <div class="col mr-2 ">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1 ">
                                Usuarios
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white"><?= $estadisticas ?></div>
                        </div>
                        <div class="col-auto hoverable">
                            <a  href="<?php echo RUTA_ABSOLUTA; ?>admin/admin">
                                <i class="fa-solid fa-user-secret fa-2x "></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100 py-2 bg-success rounded-5">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Días de trabajo de los funcionarios
                                </div>
                                <div class="h5 mb-0 font-weight-bold white"></div>
                            </div>
                            <div class="col-auto hoverable ">
                                <a  href="<?php echo RUTA_ABSOLUTA; ?>admin/trabajo">
                                    <i class="fa-solid fa-calendar fa-2x "></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100 py-2 bg-primary rounded-5">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Generar Solicitud
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-white"></div>
                            </div>
                            <div class="col-auto hoverable ">
                                <a  href="<?php echo RUTA_ABSOLUTA; ?>admin/solicitud_general">
                                    <i class="bi bi-envelope fa-2x "></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 bg-warning rounded-5">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Recepción de solicitudes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= $permisosRealizados ?></div>
                        </div>
                        <div class="col-auto hoverable ">
                            <a  href="<?php echo RUTA_ABSOLUTA; ?>admin/permisos_pendientes">
                                <!-- <i class="fas fa-inbox fa-2x "></i> -->
                                <i class="fa-solid fa-bell fa-2x "></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Carta 5 -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 bg-success rounded-5">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                Permisos aprobados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white"><?= $permisoAceptados ?></div>
                        </div>
                        <div class="col-auto hoverable ">
                            <a  href="<?php echo RUTA_ABSOLUTA; ?>permisos/aceptados">
                                <i class="fa-solid fa-check fa-2x "></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Carta 6 -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 bg-danger rounded-5">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                Permisos rechazados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white"><?= $permisoRechazados ?></div>
                        </div>
                        <div class="col-auto hoverable ">
                            <a  href="<?php echo RUTA_ABSOLUTA; ?>permisos/rechazados">
                                <!-- <i class="fas fa-inbox fa-2x "></i> -->
                                <i class="fa-solid fa-xmark fa-2x "></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Carta 7 -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 bg-success rounded-5">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                Registrar permisos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white"><?= $permisoAceptados ?></div>
                        </div>
                        <div class="col-auto hoverable ">
                            <a  href="<?php echo RUTA_ABSOLUTA; ?>admin/permisos">
                                <!-- <i class="fas fa-inbox fa-2x "></i> -->
                                <i class="fa-regular fa-registered fa-2x "></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Carta 8 -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 bg-primary rounded-5">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                Permisos registrados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white"><?= $permisoRegistrados ?></div>
                        </div>
                        <div class="col-auto hoverable ">
                            <a  href="<?php echo RUTA_ABSOLUTA; ?>admin/permisos_registrados">
                                <!-- <i class="fas fa-inbox fa-2x "></i> -->
                                <i class="fa-solid fa-check-double fa-2x "></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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


<?php
include_once("../plantilla/footer.php")
?>