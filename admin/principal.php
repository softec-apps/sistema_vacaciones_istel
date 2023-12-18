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

$titulo = "Panel";
include_once("../plantilla/header.php")
?>

    <h1 class="mt-5">Carpeta de Administradores </h1>
    <h4>El nombre del Admin es : <?php echo $nombre ?></h4>
    <h4>La Cedula del Admin es : <?php echo $cedula ?></h4>
    <h4>El Rol del usuario es : <?php echo $rol ?></h4>

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
</div>

<script>
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

</script>


<?php
include_once("../plantilla/footer.php")
?>