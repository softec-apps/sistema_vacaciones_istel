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

if ($rol !== ROL_JEFE) {
   redirect(RUTA_ABSOLUTA . "logout");
}
$message = '';
$type = '';
$flash_message = display_flash_message();

if (isset($flash_message)) {
    $message = $flash_message['message'];
    $type = $flash_message['type'];
}

$titulo = "Archivos Funcionario";
include_once "../plantilla/header.php";

include_once  "../conexion.php";
include_once  "../funciones.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $vista = archivos_individuales($pdo,$id_usuario);
    if (!empty($vista)) {
        $nombrePrueba = $vista['0']['nombres'];
     }else{
         $nombrePrueba ="";
     }

}else {
    create_flash_message(
        'Seleccione la opcion de ver archivos para acceder de maner correcta',
        'error'
    );

    redirect(RUTA_ABSOLUTA . "admin/archivosSubidos");
}


?>
 <!-- Page Heading -->
 <h1 class="h3 mb-2 text-gray-800 mt-4 ml-2">Archivos del usuario <?= $nombrePrueba ?></h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">En esta sección se ven todos los archivos del usuario</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered crud-table" id="tablaConsultaTrabajo">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Motivo del permiso</th>
                        <th class="exclude">Archivos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (empty($vista)) {
                        echo "";

                    }else {
                        foreach ($vista as $key => $valor){
                            $id_usuarios  = $valor ["id_usuarios"];
                            $id_permisos = $valor ["id_permisos"];
                            $id_archivo = $valor ["id_archivo"];
                            $motivo_permiso = $valor ["motivo_permiso"];
                            $motivo_permiso = str_replace('_', ' ', $motivo_permiso);
                            $ruta_solicita = $valor ["ruta_solicita"];
                            $ruta_aprueba = $valor ["ruta_aprueba"];
                            $ruta_registra = $valor ["ruta_registra"];
                            $rutaSolicita = verificarRuta($ruta_solicita);
                            $rutaAprueba = verificarRuta($ruta_aprueba);
                            $rutaRegistra = verificarRuta($ruta_registra);


                    ?>
                    <tr>
                        <td>
                        <?= $key + 1 ?>
                        </td>

                        <td>
                        <?= $motivo_permiso ?>
                        </td>

                        <td>
                            <?php
                            if (!empty($ruta_solicita)) {
                                echo '<a class="btn btn-success m-1" title="Solicitud firmada por el funcionario" href="' . RUTA_ABSOLUTA . $rutaSolicita . '" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';
                            }

                            if (!empty($ruta_aprueba)) {
                                echo '<a class="btn btn-primary m-1" title="Solicitud firmada por el jefe supervisor" href="' . RUTA_ABSOLUTA . $rutaAprueba . '" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';
                            }

                            if (!empty($ruta_registra)) {
                                echo '<a class="btn btn-info m-1" title="Solicitud firmada por talento humano" href="' . RUTA_ABSOLUTA . $rutaRegistra . '" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';
                            }
                            ?>

                        </td>

                    </tr>
                    <?php
                        };
                    };
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>



<?php include_once("../plantilla/footer.php")?>