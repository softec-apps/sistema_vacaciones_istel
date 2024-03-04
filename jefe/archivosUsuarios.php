
<?php
include_once "../redirection.php";
include_once "../flash_messages.php";
session_start();

if (!isset($_SESSION['id_usuarios'])) {
    redirect(RUTA_ABSOLUTA . 'logout');
}
$id_user = $_SESSION['id_usuarios'];
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
include_once("../plantilla/header.php")
?>


<div class="container-fluid mt-4">
<?php
include_once  "../conexion.php";
include_once  "../resta_solicitud.php";
include_once  "../funciones.php";
$vista = vista3($pdo,$id_user);

?>
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Todos los usuarios que tienen solicitudes hechas</h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">En esta sección se puede dirigir a los archivos relacionados con cada permiso del usuario </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered crud-table" id="tablaArchivosSubidos">
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Modalidad</th>
                        <th class="exclude">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (empty($vista)) {
                        echo "";

                    }else {
                        foreach ($vista as $key => $valor){
                            $id_usuarios  = $valor ["id_usuarios"];
                            $nombres = $valor ["nombres"];
                            $cedula = $valor ["cedula"];
                            $apellidos = $valor ["apellidos"];
                            $tiempoTrabajo = $valor ["tiempo_trabajo"];
                            if ($tiempoTrabajo == 8) {
                                $tiempoTrabajo = "Tiempo completo";
                            }elseif ($tiempoTrabajo == 4) {
                                $tiempoTrabajo = "Medio tiempo";
                            }else {
                                $tiempoTrabajo = "";
                            }

                    ?>
                    <tr>

                        <td>
                        <?= $cedula ?>
                        </td>

                        <td>
                            <?= $nombres ?>
                        </td>

                        <td>
                            <?= $apellidos ?>
                        </td>


                        <td>
                        <?= $tiempoTrabajo ?>
                        </td>

                        <td>
                            <form action="<?= RUTA_ABSOLUTA  ?>jefe/allArchivos" method="post" title="Ver todos los archivos">
                                <input type="hidden" name="id_usuario" id="usuario_id" value="<?= $id_usuarios ?>">
                                <button class="btn btn-primary m-1" type="submit" ><i class="fa-solid fa-folder-open"></i></button>
                            </form>
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
