<?php
include_once "../redirection.php";
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

?>

<h5 class="text-gray-800"> Permisos rechazados</h5>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header d-flex py-3">
        <p class="m-2 col-10 pl-0 font-weight-bold text-primary">
            Todos los permisos rechazadas
        </p>
    </div>
    <div class="card-body">
        <div class="table-responsive crud-table">
            <table class="table table-bordered" id="tablaPermisosRechazados">
                <thead>
                    <tr>
                        <th>Tipo de permiso solicitado</th>
                        <th>Días solicitados</th>
                        <th>Horas solicitadas</th>
                        <th>Motivo del rechazo</th>
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
                            $permisoAceptadoClass ="bg-danger text-white text-center";
                            $title = "Solicitud rechazada";
                            $permiso_aceptado = '<i class="fa-lg fa-solid fa-xmark mt-3"></i>';
                            $ver = null;
                        }elseif ($permiso_aceptado == 1 || $permiso_aceptado == 3) {
                            $permiso_aceptado = '<i class="fa-lg fa-solid fa-check mt-3"></i>';
                            $permisoAceptadoClass ="bg-success text-white text-center";
                            $title = "Solicitud aceptada";
                            $ver ='<form action="../datos_individuales" method="POST" class="d-inline-block m-1">
                                        <input type="hidden" name="id_permisos" value="' . $id_permisos .'">
                                        <button class="btn btn-info m-1" title="Ver solicitud aprobada">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </form>';
                        }elseif ($permiso_aceptado == 0) {
                            $permisoAceptadoClass ="bg-primary text-white text-center";
                            $title = "Solicitud en proceso";
                            $permiso_aceptado = '<i class="fa-lg fa-solid fa-sync fa-spin mt-3"></i>';
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
                        <?= $motivo_rechazo?>
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

<script>
    $(".cerrarModal").click(function(){
        $("#registrar_vacaciones").modal('hide');
    });
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

</script>
<?php include_once("../plantilla/footer.php")?>
