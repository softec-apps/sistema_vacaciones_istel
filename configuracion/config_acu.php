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

$titulo = "Configuracion";
include_once "../plantilla/header.php";
include_once "../conexion.php";
//Configuracion de limtes de dias

function seleccionar($pdo){
    try {
        $consulta = "SELECT limiteVacaciones, diasPorAñoTrabajado, diasPorAño FROM dias_trabajo";
        $stmt = $pdo->prepare($consulta);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Asignar los valores a las variables
        $limiteVacaciones = $resultado["limiteVacaciones"];
        $diasPorAnoTrabajado = $resultado["diasPorAñoTrabajado"];
        $diasPorAno = $resultado["diasPorAño"];

        // Puedes devolver las variables si es necesario
        return [
            'limiteVacaciones' => $limiteVacaciones,
            'diasPorAnoTrabajado' => $diasPorAnoTrabajado,
            'diasPorAno' => $diasPorAno
        ];
    } catch (PDOException $e) {
        echo "Error de excepción: " . $e->getMessage();
    }

}

$seleccionar = seleccionar($pdo);
// Puedes acceder a las variables individualmente
$limiteVacaciones = $seleccionar['limiteVacaciones'];
$diasPorAnoTrabajado = $seleccionar['diasPorAnoTrabajado'];
$diasPorAno = $seleccionar['diasPorAno'];

?>

<div class="container-fluid">
<script src="js/flash_messages.js"></script>
<button type="button" class="btn btn-info m-3" data-toggle="modal" data-target="#modalConf">
    Actualizar datos de Configuracion
</button>
</div>
    <div class="row m-4 text-center">
    <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-3 ">
            <div class="card shadow border-left-success py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col ">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Limite Vacaciones </div>
                            <div class="h5 font-weight-bold text-gray-800"><?= $limiteVacaciones ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Dias por Cada año Trabajado</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $diasPorAnoTrabajado ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-3" >
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Dias por Año</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $diasPorAno ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalConf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Actualizar datos de Configuracion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form method="post" action="<?php echo RUTA_ABSOLUTA ?>configuracion/procesar" id="formularioD">

                    <div class="form-floating mb-3">
                        <div >
                            <label >Limite de Vacaciones </label>
                            <input class="form-control" name="limiteVac" type="number" value="<?= $limiteVacaciones ?>"/>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                        <label >Dias por año Trabajado</label>
                        <input class="form-control" type="number" name="diasTrAño" value="<?= $diasPorAnoTrabajado ?>"/>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <div>
                        <label >Dias por Año </label>
                        <input class="form-control" type="number" name="diasXaño" value = "<?= $diasPorAno ?>"/>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once("../plantilla/footer.php")?>