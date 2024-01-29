<?php
include_once "../conexion.php";
if ($_POST) {
    $limiteVac = $_POST["limiteVac"];
    $diasTrAño = $_POST["diasTrAño"];
    $diasXaño = $_POST["diasXaño"];
    function actualizar($pdo, $limiteVacaciones, $diasPorAñoTrabajado, $diasPorAño)
    {
        try {
            $consulta = "UPDATE dias_trabajo SET limiteVacaciones=:limiteVacaciones, diasPorAñoTrabajado=:diasPorAnoTrabajado, diasPorAño=:diasPorAno ;";
            $stmt = $pdo->prepare($consulta);
            $stmt->bindParam(':limiteVacaciones', $limiteVacaciones, PDO::PARAM_STR);
            $stmt->bindParam(':diasPorAnoTrabajado', $diasPorAñoTrabajado, PDO::PARAM_STR);
            $stmt->bindParam(':diasPorAno', $diasPorAño, PDO::PARAM_STR);
            $stmt->execute();
            echo "Configuración Actualizada";
        } catch (PDOException $e) {
            echo "Error de excepción: " . $e->getMessage();
        }
    }
    $funcion = actualizar($pdo, $limiteVac, $diasTrAño, $diasXaño);

} else {
    echo "No se enviaron datos";
}
?>
