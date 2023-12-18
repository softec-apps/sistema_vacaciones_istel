<?php

function redirect($ruta){
    header("Location: $ruta ");
    exit();
}

$ruta_absoluta = "/sistema_vacaciones/";
define('RUTA_ABSOLUTA', $ruta_absoluta);
// Define constantes para los nombres de roles
define('ROL_FUNCIONARIO', 'Funcionario');
define('ROL_JEFE', 'Jefe');
define('ROL_ADMIN', 'admin');
define('ROL_TALENTO_HUMANO', 'Talento_Humano');
?>