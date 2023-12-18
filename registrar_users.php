<?php
include_once "redirection.php";
include_once "funciones.php";
include_once "conexion.php";


if ($_POST) {
    $cedula =$_POST["cedula"];
    $nombres =$_POST["nombres"];
    $apellidos =$_POST["apellidos"];
    $email =$_POST["email_A"];
    $usuario =$_POST["user_A"];
    $clave = $_POST["password_A"];
    $rol = $_POST["roles1"];
    $fecha_ingreso = $_POST["fecha_ingreso"];

    $id_insertado  = insertUsers($cedula,$nombres,$apellidos,$email,$usuario,$clave,$rol,$fecha_ingreso,$pdo);

    if ($id_insertado && $rol === "Funcionario") {

        $id_usuarios = $id_insertado;
        $insertado_dias_work =  calculo_unico_insert($id_usuarios,$pdo);

        if ($insertado_dias_work) {

            $id_dias_trabajo = $insertado_dias_work;

            echo"DATOS EN LA TABLA VACACIONES REGISTRADOS".$id_dias_trabajo. "EL ID=" .$id_usuarios ;

        }
    }else {
        redirect("admin/admin");
    }

}

?>