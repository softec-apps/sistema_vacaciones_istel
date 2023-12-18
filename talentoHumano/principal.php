<?php

include_once "../redirection.php";
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
$titulo = "TalentoH";
include_once("../plantilla/header.php")
?>

    <h1>Carpeta de Talento Humano </h1>
    <h4>El nombre del usuario es : <?php echo $nombre ?></h4>
    <h4>La Cedula del usuario es : <?php echo $cedula ?></h4>
    <h4>El Rol del usuario es : <?php echo $rol ?></h4>

<?php
include_once("../plantilla/footer.php")
?>