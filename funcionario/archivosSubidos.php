<?php
include_once "../redirection.php";
include_once "../flash_messages.php";
 session_start();

if (!isset($_SESSION['id_usuarios'])) {
    redirect(RUTA_ABSOLUTA . "logout");
}
$id = $_SESSION['id_usuarios'];
$cedula = $_SESSION['cedula'];
$nombreFuncionario = $_SESSION['nombres'];
$apellidosFuncionario = $_SESSION['apellidos'];
$rol = $_SESSION['rol'];
$fecha_ingreso = $_SESSION['fecha_ingreso'];

if ($rol != 'Funcionario') {
    redirect(RUTA_ABSOLUTA . "logout");
}

$message = '';
$type = '';
$flash_message = display_flash_message();

if (isset($flash_message)) {
    $message = $flash_message['message'];
    $type = $flash_message['type'];
}

$titulo = "Archivos subidos";
include_once "../plantilla/header.php";


include_once  "funcionesCalcular.php";
$vista = datosdeArchivosDelUsuario($pdo,$id);

$nombrePrueba = $nombreFuncionario;




?>


<div class="mx-4 p-1">
<h1 class="h3 mb-2 text-gray-800 mt-4 ml-2">Archivos subidos en el sistema</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">En esta parte se muestra el archivo del usuario con su descripcion</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered crud-table" id="tablaConsultaTrabajo">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Descripcion</th>
                            <th class="exclude">Archivos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (empty($vista)) {
                            echo "";

                        }else {
                            foreach ($vista as $key => $valor){
                                $id_permisos = $valor ["id_permisos"];
                                $id_archivo = $valor ["id_archivo"];
                                $descripcion_solicita = $valor ["descripcion_solicita"];
                                $ruta_solicita = $valor ["ruta_solicita"];
                                $rutaSolicita = verificarRuta($ruta_solicita);
                        ?>
                        <tr>
                            <td>
                            <?= $key + 1 ?>
                            </td>


                            <td>
                                <?= $descripcion_solicita ?>
                            </td>


                            <td>
                                <?php
                                if (!empty($ruta_solicita)) {
                                    echo '<a class="btn btn-success m-1" title="Solicitud firmada por el funcionario" href="' . RUTA_ABSOLUTA . $rutaSolicita . '" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';
                                }

                                if (!empty($ruta_aprueba)) {
                                    echo '<a class="btn btn-primary m-1" title="Solicitud firmada por el jefe supervisor" href="' . RUTA_ABSOLUTA . $ruta_aprueba . '" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';
                                }

                                if (!empty($ruta_registra)) {
                                    echo '<a class="btn btn-info m-1" title="Solicitud firmada por talento humano" href="' . RUTA_ABSOLUTA . $ruta_registra . '" target="_blank"><i class="fa-solid fa-file-arrow-down"></i></a>';
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
</div>



<?php include_once("../plantilla/footer.php")?>