<?php

    $user = "root";
    $password = "";
    $dsn = "mysql:host=localhost;dbname=sistema_vacaciones";
    try {

        $pdo = new PDO($dsn, $user, $password );
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        echo "No se logro la conexion" .$e->getMessage();
    }

?>