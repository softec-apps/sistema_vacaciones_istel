<?php
include_once "funciones.php";
include_once "conexion.php";


if ($_POST) {
    $cedula =$_POST["cedula"];
    $nombres =$_POST["nombres"];
    $apellidos =$_POST["apellidos"];
    $email =$_POST["email_A"];
    $usuario =$_POST["user_A"];
    $clave = $_POST["password_A"];
    $rol = $_POST["roles"];


    insertUsers($cedula,$nombres,$apellidos,$email,$usuario,$clave,$rol,$pdo);

}

?>