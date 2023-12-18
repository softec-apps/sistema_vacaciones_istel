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

$titulo = "Permisos Registrados";
include_once("../plantilla/header.php")
?>

                <div class="container-fluid mt-5">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Permisos Aprobados</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Permisos Aprobados</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive crud-table">
                                <table class="table table-bordered" id="tabla_permisos_registrados">
                                    <thead>
                                        <tr>
                                            <th>Cedula</th>
                                            <th>Nombres</th>
                                            <th>Provincia</th>
                                            <th>Regimen</th>
                                            <th>Coordinacion</th>
                                            <th>Direccion o unidad</th>
                                            <th>Fecha emitida</th>
                                            <th>Tipo permiso</th>
                                            <th>Desc permiso</th>
                                            <th>Dias Solicitados</th>
                                            <th>Horas Solicitadas</th>
                                            <th>Fecha  Inicio</th>
                                            <th>Fecha  Fin</th>
                                            <th>Hora  Inicio</th>
                                            <th>Hora  Fin</th>
                                            <th>Usuario Solicita</th>
                                            <th>Usuario Aprueba</th>
                                            <th>Usuario Registra</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>
                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>

                                            <td>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

<?php include_once("../plantilla/footer.php")?>
