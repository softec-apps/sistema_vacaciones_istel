<?php

include_once "../redirection.php";
include_once "../flash_messages.php";
 session_start();

 if (!isset($_SESSION['id_usuarios'])) {
    redirect("../logout");
 }
 $cedula = $_SESSION['cedula'];
 $nombre = $_SESSION['nombres'];
 $rol = $_SESSION['rol'];

 if ($rol != 'Talento_Humano') {
    redirect("../logout");
}

$message = '';
$type = '';
$flash_message = display_flash_message();

if (isset($flash_message)) {
    $message = $flash_message['message'];
    $type = $flash_message['type'];
}

$titulo = "TalentoH";
include_once("../plantilla/header.php")
?>
    <style>
        .card-body .hoverable .fa-2x:hover  {
            color:  #4e73df;
        }
        .card-body .verde .fa-2x:hover  {
            color:  #1cc88a;
        }
        .card-body .amarillo .fa-2x:hover  {
            color:  #f6c23e;
        }
        .fa-2x{
            color: #d1d1d1;
        }
    </style>
<div class="container-fluid mt-5">
    <div class="row">
        <!-- Carta 1 -->
        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Funcionarios</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">5</div>
                        </div>
                        <div class="col-auto hoverable">
                            <a href="<?php echo RUTA_ABSOLUTA; ?>talentoHumano/registrarFuncionario">
                                <i class="fa-solid fa-user-plus fa-2x"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carta 2 -->
        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Permisos Aceptados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">10</div>
                        </div>
                        <div class="col-auto verde">
                            <a href="<?php echo RUTA_ABSOLUTA; ?>talentoHumano/permisosAprobados">
                                <i class="fa-solid fa-envelope-open-text fa-2x"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carta 3 -->
        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Permisos Registrados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">10</div>
                        </div>
                        <div class="col-auto amarillo ">
                            <a href="<?php echo RUTA_ABSOLUTA; ?>talentoHumano/permisosRegistrados">
                                <i class="fas fa-check-circle fa-2x "></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


<?php
include_once("../plantilla/footer.php")
?>