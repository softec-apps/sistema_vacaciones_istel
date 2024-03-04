<?php
include_once "../redirection.php";
include_once "../flash_messages.php";
session_start();

if (!isset($_SESSION['id_usuarios'])) {

    redirect(RUTA_ABSOLUTA . "logout");
}
$cedula = $_SESSION['cedula'];
$nombre = $_SESSION['nombres'];
$rol = $_SESSION['rol'];
$fecha_ingreso = $_SESSION['fecha_ingreso'];

if ($rol != 'jefe') {

    redirect(RUTA_ABSOLUTA . "logout");
}

$message = '';
$type = '';
$flash_message = display_flash_message();

if (isset($flash_message)) {
    $message = $flash_message['message'];
    $type = $flash_message['type'];
}

$titulo = "Jefe";
include_once("../plantilla/header.php")
?>

    <style>
        .card-body .hoverable .fa-2x:hover  {
            color:  #4e73df;
        }
        .card-body .verde .fa-2x:hover  {
            color:  #1cc88a;
        }
        .card-body .rojo .fa-2x:hover  {
            color:  #e74a3b;
        }
        .card-body .info .fa-2x:hover  {
            color:  #36b9cc;
        }
        .fa-2x{
            color: #d1d1d1;
        }
    </style>
<div class="container-fluid mt-5">
    <div class="row">
        <!-- Carta 1 -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Recepcion de Solicitudes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                        </div>
                        <div class="col-auto hoverable">
                            <a href="<?php echo RUTA_ABSOLUTA; ?>jefe/permisosSolicitados">
                                <i class="fas fa-inbox fa-2x"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carta 2 -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Permisos Aprobados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                        </div>
                        <div class="col-auto verde">
                            <a href="<?php echo RUTA_ABSOLUTA; ?>jefe/permisosAprobados">
                                <i class="fa-regular fa-circle-check fa-2x"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carta 3 -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Permisos Rechazados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                        </div>
                        <div class="col-auto rojo">
                            <a href="<?php echo RUTA_ABSOLUTA; ?>jefe/permisosRechazados">
                                <i class="fa-regular fa-circle-xmark fa-2x"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carta 4 -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Dias de Vacaciones</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                        </div>
                        <div class="col-auto info ">
                            <a href="<?php echo RUTA_ABSOLUTA; ?>jefe/trabajoFuncionarios">
                                <i class="fas fa-umbrella-beach fa-2x"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php include_once("../plantilla/footer.php")?>