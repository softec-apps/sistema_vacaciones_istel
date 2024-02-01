<?php
include_once "../redirection.php";
include_once "../flash_messages.php";
include_once "../conexion.php";
if ($_POST) {
    $limiteVac = $_POST["limiteVac"];
    $diasTrAño = $_POST["diasTrAño"];
    $diasXaño = $_POST["diasXaño"];
    function actualizar($pdo, $limiteVacaciones, $diasPorAñoTrabajado, $diasPorAño)
    {
        try {
            $consulta = "UPDATE configuracion SET limiteVacaciones=:limiteVacaciones, diasPorAño=:diasPorAnoTrabajado, diasAnuales=:diasAnuales ;";
            $stmt = $pdo->prepare($consulta);
            $stmt->bindParam(':limiteVacaciones', $limiteVacaciones, PDO::PARAM_STR);
            $stmt->bindParam(':diasPorAnoTrabajado', $diasPorAñoTrabajado, PDO::PARAM_STR);
            $stmt->bindParam(':diasAnuales', $diasPorAño, PDO::PARAM_STR);
            $stmt->execute();

            create_flash_message(
                'Configuracion Actualizada Exitosamente',
                'success'
            );

            redirect(RUTA_ABSOLUTA . "configuracion/configuracionAcumulados");
        } catch (PDOException $e) {
            echo "Error de excepción: " . $e->getMessage();
        }
    }
    $funcion = actualizar($pdo, $limiteVac, $diasTrAño, $diasXaño);

} else {
    echo "No se enviaron datos";
}
?>
