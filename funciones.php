<?php
include_once "redirection.php";
//Funcion de Sesi0on Activa
function get_session(){
    if (session_status() == PHP_SESSION_NONE) {
        // Solo inicia la sesión si no hay una sesión activa
        session_start();
    }
}

//funcion para iniciar sesion
function star_sesion($usuario,$clave,$pdo){
    try {

        $inicio_sesion =('SELECT id_usuarios,cedula,nombres,apellidos,usuario,contraseña,rol,fecha_ingreso FROM usuarios WHERE usuario = :usuario');
        //Premaramos la consulta
        $stmt = $pdo->prepare($inicio_sesion);

        //Ejecutamos la consulta
        $stmt->execute([':usuario'=>$usuario]);

        $existe = $stmt->rowCount()==1;


        //Verficamos si encontro un usuario co las credenciales ingresadas
        if ($existe>0) {
            //obtenemos la informacion del usuario
            $info = $stmt->fetch(PDO::FETCH_ASSOC);

            $password_bd = $info['contraseña'];
            $rol = $info['rol'];

            $pass_c = $clave;

            if ($password_bd == $pass_c) {
                get_session();
                $_SESSION['id_usuarios'] = $info['id_usuarios'];
                $_SESSION['cedula'] = $info['cedula'];
                $_SESSION['nombres'] = $info['nombres'];
                $_SESSION['apellidos'] = $info['apellidos'];
                $_SESSION['rol'] = $info['rol'];
                $_SESSION['fecha_ingreso'] = $info['fecha_ingreso'];

                if ($rol == ROL_FUNCIONARIO) {

                    redirect("funcionario/dashboard");

                }elseif ($rol == ROL_JEFE) {

                    redirect("jefe/dashboard");

                }elseif ($rol == ROL_ADMIN) {

                    redirect("admin/dashboard");

                }elseif ($rol == ROL_TALENTO_HUMANO) {

                    redirect("talento_h/dashboard");

                }else {
                    echo("error de validacion de roles");
                }
            }else{

                echo "La contraseña no coincide";

            }
        } else {
            // Credenciales incorrectas, redirigir al usuario al formulario de inicio de sesión
            echo "Error en las credenciales";
        }

    } catch (PDOException $e) {
        echo "Error de exepcion" .$e->getMessage();
    }
}
//Insertar datos e un usuario
function insertUsers($cedula,$nombres,$apellidos,$email,$usuario,$clave,$rol,$fecha_ingreso,$pdo){
    try {
        // Consulta de inserción con marcadores de posición
        $insertar_users = "INSERT INTO usuarios (cedula, nombres, apellidos, email, usuario, contraseña, rol,fecha_ingreso)VALUES (:cedula, :nombres, :apellidos, :email, :usuario, :clave, :rol, :fecha_ingreso)";

        // Preparamos la consulta
        $stmt = $pdo->prepare($insertar_users);

        // Parámetros para la inserción
        $params = [
            ':cedula' => $cedula,
            ':nombres' => $nombres,
            ':apellidos' => $apellidos,
            ':email' => $email,
            ':usuario' => $usuario,
            ':clave' => $clave,
            ':rol' => $rol,
            ':fecha_ingreso' => $fecha_ingreso,
        ];

        // Ejecutamos la consulta de inserción
        $stmt->execute($params);

        // Obtener el ID del último usuario insertado
        $id_usuario_insertado = $pdo->lastInsertId();

        return $id_usuario_insertado;

    } catch (PDOException $e) {
        //Error de cedula duplicada
        if ($e->getCode() == '23000') {
            echo "Error de repetición de cédula: La cédula ya existe en la base de datos.";
        } else {
            echo "Error de excepción: " . $e->getMessage();
        }
    }

}
//Mostrar Datos de un Usuario
function mostrarUsuarios($pdo){
    try {

        $id=1;
        $consuta_usuarios = "SELECT id_usuarios,cedula,nombres,apellidos,email,rol,fecha_ingreso FROM usuarios WHERE id_usuarios <> :id_usuarios";
        //Premaramos la consulta
        $stmt = $pdo->prepare($consuta_usuarios);

        //Ejecutamos la consulta
        $stmt->execute([':id_usuarios'=>$id]);
        // Obtener los resultados
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultados;

    }catch (PDOException $e) {
        echo "Error de exepcion" .$e->getMessage();
    }
}
//Actualizar datos de un usuario
function actualizar_usuario($pdo,$id_usuarios,$cedula,$nombres,$apellidos,$email,$rol,$fecha_ingreso){

    try {
        $act_user = "UPDATE usuarios SET cedula=:cedula,nombres=:nombres,apellidos=:apellidos,email=:email,rol=:rol,fecha_ingreso=:fecha_ingreso WHERE id_usuarios =:id_usuarios";
        //Premaramos la consulta
        $stmt = $pdo->prepare($act_user);

        //Parametros con sus valores
        $stmt->bindParam(':id_usuarios',$id_usuarios,PDO::PARAM_INT);
        $stmt->bindParam(':cedula',$cedula,PDO::PARAM_STR);
        $stmt->bindParam(':nombres',$nombres,PDO::PARAM_STR);
        $stmt->bindParam(':apellidos',$apellidos,PDO::PARAM_STR);
        $stmt->bindParam(':email',$email,PDO::PARAM_STR);
        $stmt->bindParam(':rol',$rol,PDO::PARAM_STR);
        $stmt->bindParam(':fecha_ingreso',$fecha_ingreso,PDO::PARAM_STR);

        //Ejecutamos la consulta con los parametros
        $stmt->execute();

        echo"Datos Actualizados";
    } catch (PDOException $e) {
        echo "Error de exepcion" .$e->getMessage();
    }

}

function eliminar_user($pdo,$id_usuario){
    try {
        $eliminar_users = "DELETE FROM usuarios WHERE id_usuarios=:id_usuarios";
        //Premaramos la consulta
        $stmt = $pdo->prepare($eliminar_users);

        //Parametros con sus valores
        $stmt->bindParam(':id_usuarios',$id_usuario,PDO::PARAM_INT);


        //Ejecutamos la consulta con los parametros
        $stmt->execute();
        echo "Eliminado correctamente";

    } catch (PDOException $e) {
        echo "Error de exepcion" .$e->getMessage();
    }
}

function cons_multitabla($pdo){

    try {
        $const_mult="SELECT dias_trabajo.id_trabajo, usuarios.id_usuarios, dias_trabajo.dias_laborados, 		dias_trabajo.fecha_inicio, dias_trabajo.fecha_actual, usuarios.cedula, usuarios.nombres, usuarios.apellidos, 	usuarios.rol, usuarios.fecha_ingreso
        FROM dias_trabajo, usuarios
        WHERE usuarios.id_usuarios = dias_trabajo.id_usuarios;";
        $stmt = $pdo->prepare($const_mult);

        //Ejecutamos la consulta
        $stmt->execute();
        // Obtener los resultados
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultados;

    } catch (PDOException $e) {
        echo "Error de exepcion" .$e->getMessage();
    }
}

function calculo_unico_insert($id_usuarios,$pdo){
    try {

        // Comenzar la transacción
        $pdo->beginTransaction();
        // Supongamos que tienes el ID del usuario y la fecha actual
        // ID del usuario
        $fecha_actual = date('Y-m-d'); // Fecha actual
        $fecha_inicio =  date('Y-m-d');
        // Obtener la fecha de ingreso del usuario
        $query = "SELECT fecha_ingreso FROM usuarios WHERE id_usuarios = :id";
        $statement = $pdo->prepare($query);
        $statement->bindParam(':id', $id_usuarios, PDO::PARAM_INT);
        $statement->execute();

        $resultado = $statement->fetch(PDO::FETCH_ASSOC);
        $fecha_ingreso_usuario = $resultado['fecha_ingreso'];

        // Calcular los días trabajados
        $dias_laborados = (strtotime($fecha_actual) - strtotime($fecha_ingreso_usuario)) / (60 * 60 * 24);

        // Insertar el registro en la tabla dias_trabajados
        $queryInsert = "INSERT INTO dias_trabajo (id_usuarios,dias_laborados,fecha_inicio,fecha_actual ) VALUES ( :id_usuarios, :dias_laborados,:fecha_inicio,:fecha_actual)";
        $statementInsert = $pdo->prepare($queryInsert);
        $statementInsert->bindParam(':id_usuarios', $id_usuarios, PDO::PARAM_INT);
        $statementInsert->bindParam(':dias_laborados', $dias_laborados, PDO::PARAM_INT);
        $statementInsert->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
        $statementInsert->bindParam(':fecha_actual', $fecha_actual, PDO::PARAM_STR);
        $statementInsert->execute();


        // Obtener el ID del último registro insertado
        $ultimoIdInsertado = $pdo->lastInsertId();

        // Confirmar la transacción si todo ha ido bien
        $pdo->commit();

        // Retornar el ID del último registro insertado
        return $ultimoIdInsertado;

    } catch (PDOException $e) {
        // Deshacer la transacción en caso de error
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();

        //Retorna falso en caso de un error para una futura comparacion
        return false;
    }
}
function calcular_actualizar($pdo){
    try {
        // Comenzar la transacción
        $pdo->beginTransaction();

        $fecha_actual = date('Y-m-d'); // Fecha actual

        // Obtener las fechas de ingreso de todos los usuarios
        $query = "SELECT id_usuarios, fecha_ingreso FROM usuarios";
        $statement = $pdo->prepare($query);
        $statement->execute();

        // Iterar sobre los resultados y actualizar los registros en dias_trabajo
        while ($resultado = $statement->fetch(PDO::FETCH_ASSOC)) {
            $id_usuarios = $resultado['id_usuarios'];
            $fecha_ingreso_usuario = $resultado['fecha_ingreso'];

            // Calcular los días trabajados
            $dias_laborados = (strtotime($fecha_actual) - strtotime($fecha_ingreso_usuario)) / (60 * 60 * 24);

            // Actualizar el registro en la tabla dias_trabajo
            $calcular_act = "UPDATE dias_trabajo SET dias_laborados = :dias_laborados, fecha_actual = :fecha_actual WHERE id_usuarios = :id_usuarios";

            // Preparo la consulta
            $stmt = $pdo->prepare($calcular_act);

            // Pasamos los parametros con sus valores
            $stmt->bindParam(':dias_laborados', $dias_laborados, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_actual', $fecha_actual, PDO::PARAM_STR);
            $stmt->bindParam(':id_usuarios', $id_usuarios, PDO::PARAM_INT);

            // Ejecutamos la consulta con los parametros
            $stmt->execute();
        }

        // Confirmar la transacción si todo ha ido bien
        $pdo->commit();

        // Retorna verdadero para una futura comparacion
        return true;
    } catch (PDOException $e) {
        // Deshacer la transacción en caso de error
        $pdo->rollBack();
        echo "Error de exepcion" . $e->getMessage();

        // Retorna falso en caso de un error para una futura comparación
        return false;
    }

}

function insert_table_vac($id_de_trabajo,$id_de_usuario, $pdo){

    try {
        $cons_insert ="INSERT INTO dias_vacaciones (id_de_usuario,id_de_trabajo,dias_totales_vacaciones,dias_totales_acumulados,dias_imite_acumulados,dias_limite_vacaciones)VALUES (:id_de_usuario,:id_de_trabajo,:dias_totales_vacaciones,:dias_totales_acumulados,:dias_imite_acumulados,:dias_limite_vacaciones)";

        $stmt = $pdo->prepare($cons_insert);
        $stmt->bindParam(':id_de_usuario', $id_de_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_de_trabajo', $id_de_trabajo, PDO::PARAM_INT);
        $stmt->bindParam(':dias_totales_vacaciones', $dias_totales_vacaciones, PDO::PARAM_STR);
        $stmt->bindParam(':dias_totales_acumulados', $dias_totales_acumulados, PDO::PARAM_STR);
        $stmt->bindParam(':dias_imite_acumulados', $dias_imite_acumulados, PDO::PARAM_STR);
        $stmt->bindParam(':dias_limite_vacaciones', $dias_limite_vacaciones, PDO::PARAM_STR);
        $stmt->execute();

    } catch (PDOExeption $e) {
        return "Error de exepcion" . $e->getMessage();
    }

}
?>