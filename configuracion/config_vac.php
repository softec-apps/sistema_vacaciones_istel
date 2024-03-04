<?php
include_once "../redirection.php";
include_once "../flash_messages.php";
include_once "../conexion.php";
if ($_POST) {
    $limiteVac = $_POST["limiteVac"];
    $diasTrAño = $_POST["diasTrAño"];
    $diasXaño = $_POST["diasXaño"];

    // Validar que los valores no sean negativos o iguales a 0
    if ($limiteVac <= 0 || $diasTrAño <= 0 || $diasXaño <= 0) {
        create_flash_message(
            'Los valores no pueden ser negativos o iguales a 0',
            'error'
        );
        redirect(RUTA_ABSOLUTA . "configuracion/configuracionAcumulados");
    }

    function actualizar($pdo, $limiteVacaciones, $diasPorAñoTrabajado, $diasPorAño,)
    {
        try {
            $consulta = "UPDATE configuracion SET limiteVacaciones=:limiteVacaciones, diasPorAño=:diasPorAnoTrabajado, diasAnuales=:diasAnuales";
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
            create_flash_message(
                'Ocurrio un error con el sistema',
                'error'
            );
            redirect(RUTA_ABSOLUTA . "configuracion/configuracionAcumulados");
        }
    }
    $funcion = actualizar($pdo, $limiteVac, $diasTrAño, $diasXaño);

} else {
    create_flash_message(
        'No se enviaron datos',
        'error'
    );
    redirect(RUTA_ABSOLUTA . "configuracion/configuracionAcumulados");
}
?>
