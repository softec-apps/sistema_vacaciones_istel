<?php
include_once "redirection.php";
include_once "flash_messages.php";
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
                    create_flash_message(
                        'Inicio de sesion correcto',
                        'success'
                    );

                    redirect("funcionario/dashboard");

                }elseif ($rol == ROL_JEFE) {
                    create_flash_message(
                        'Inicio de sesion correcto',
                        'success'
                    );

                    redirect("jefe/dashboard");

                }elseif ($rol == ROL_ADMIN) {

                    create_flash_message(
                        'Inicio de sesion correcto',
                        'success'
                    );

                    redirect("admin/dashboard");

                }elseif ($rol == ROL_TALENTO_HUMANO) {
                    create_flash_message(
                        'Inicio de sesion correcto',
                        'success'
                    );

                    redirect("talento_h/dashboard");

                }else {
                    echo("error de validacion de roles");
                }
            }else{

                create_flash_message(
                    'La contraseña no coincide',
                    'error'
                );
                // redirect("logout");
            }
        } else {
            // Credenciales incorrectas, redirigir al usuario al formulario de inicio de sesión
            create_flash_message(
                'Error en las credenciales',
                'error'
            );
            // redirect("logout");
        }

    } catch (PDOException $e) {
        echo "Error de exepcion" .$e->getMessage();
    }
}
//Insertar datos e un usuario
function insertUsers($cedula, $nombres, $apellidos, $email, $usuario, $clave, $rol, $fecha_ingreso,$tiempo_trabajo, $pdo)
{
    try {

        // Comenzar la transacción
        $pdo->beginTransaction();

        // Consulta de inserción con marcadores de posición
        $insertar_users = "INSERT INTO usuarios (cedula, nombres, apellidos, email, usuario, contraseña, rol, fecha_ingreso,tiempo_trabajo) VALUES (:cedula, :nombres, :apellidos, :email, :usuario, :clave, :rol, :fecha_ingreso, :tiempo_trabajo)";

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
            ':tiempo_trabajo' => $tiempo_trabajo,
        ];

        // Ejecutamos la consulta de inserción
        $stmt->execute($params);

        // Obtener el ID del último usuario insertado
        $id_usuario_insertado = $pdo->lastInsertId();

        // Verificar si el rol es "funcionario" antes de ejecutar la función calculo_unico_insert
        if ($rol == "Funcionario") {
            // Ejecutar la función calculo_unico_insert
            $id_calculo_insertado = calculo_unico_insert($id_usuario_insertado, $tiempo_trabajo, $pdo);

            // Validar si la función calculo_unico_insert se ejecutó correctamente
            if (is_numeric($id_calculo_insertado)) {
                // Confirmar la transacción si todo ha ido bien
                $pdo->commit();
                return $id_usuario_insertado;
            } else {
                // Deshacer la transacción si hay un error en la función calculo_unico_insert
                $pdo->rollBack();
                return "Error al ejecutar la función calculo_unico_insert: " . $id_calculo_insertado;
            }
        } else {
            // Confirmar la transacción si el rol no es "funcionario"
            $pdo->commit();
            return $id_usuario_insertado;
        }


    } catch (PDOException $e) {
        $pdo->rollBack();
        // Error de cédula duplicada
        if ($e->getCode() == '23000') {
            // Error de email duplicado
            if (strpos($e->getMessage(), "'email'") !== false) {
                return "Error de repetición de email: El email ya existe en la base de datos.";
            } else {
                return "Error de repetición de cédula: La cédula ya existe en la base de datos.";
            }
        } else {
            return "Error de excepción: " . $e->getMessage();
        }
    }
}

//Mostrar Datos de un Usuario
function mostrarUsuarios($pdo){
    try {

        $id=1;
        $consuta_usuarios = "SELECT id_usuarios,cedula,nombres,apellidos,usuario,contraseña,email,rol,fecha_ingreso,tiempo_trabajo FROM usuarios WHERE id_usuarios <> :id_usuarios";
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
function actualizar_usuario($pdo,$id_usuarios,$cedula,$nombres,$apellidos,$email,$rol,$fecha_ingreso,$tiempo_trabajo){

    try {
        $act_user = "UPDATE usuarios SET cedula=:cedula,nombres=:nombres,apellidos=:apellidos,email=:email,rol=:rol,fecha_ingreso=:fecha_ingreso,tiempo_trabajo=:tiempo_trabajo WHERE id_usuarios =:id_usuarios";
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
        $stmt->bindParam(':tiempo_trabajo',$tiempo_trabajo,PDO::PARAM_INT);

        //Ejecutamos la consulta con los parametros
        $stmt->execute();
        return "Datos Actualizados";
    } catch (PDOException $e) {
        return "Error de exepcion" .$e->getMessage();
    }

}
//Eliminar Usuario
function eliminar_user($pdo,$id_usuario){
    try {
        $eliminar_users = "DELETE FROM usuarios WHERE id_usuarios=:id_usuarios";
        //Premaramos la consulta
        $stmt = $pdo->prepare($eliminar_users);

        //Parametros con sus valores
        $stmt->bindParam(':id_usuarios',$id_usuario,PDO::PARAM_INT);


        //Ejecutamos la consulta con los parametros
        $stmt->execute();

        return "Usuario Eliminado";

    } catch (PDOException $e) {
        echo "Error de exepcion" .$e->getMessage();
    }
}
//Consultar datos de la tabla de dias de trabajo de los funcionarios
function cons_multitabla($pdo){

    try {
        $const_mult="SELECT dias_trabajo.id_trabajo, usuarios.id_usuarios, dias_trabajo.dias_laborados,dias_trabajo.horas_trabajadas,dias_trabajo.fecha_inicio, dias_trabajo.fecha_actual, usuarios.cedula, usuarios.nombres, usuarios.apellidos,usuarios.rol, usuarios.fecha_ingreso,dias_trabajo.dias_totales_vac_user,dias_trabajo.dias_totales_acu_user,usuarios.tiempo_trabajo
        FROM dias_trabajo, usuarios
        WHERE usuarios.id_usuarios = dias_trabajo.id_usuarios AND usuarios.rol = 'Funcionario';";
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

function calculo_unico_insert($id_usuario_insertado, $tiempo_trabajo, $pdo)
{
    try {

        // Obtener la fecha de ingreso del usuario
        $query = "SELECT fecha_ingreso FROM usuarios WHERE id_usuarios = :id";
        $statement = $pdo->prepare($query);
        $statement->bindParam(':id', $id_usuario_insertado, PDO::PARAM_INT);
        $statement->execute();

        $resultado = $statement->fetch(PDO::FETCH_ASSOC);
        $fecha_ingreso_usuario = $resultado['fecha_ingreso'];

        // Crear objetos DateTime para las fechas
        $fecha_actual_obj = new DateTime(date('Y-m-d'));

        $fecha_ingreso_obj = new DateTime($fecha_ingreso_usuario);

        // Calcular los días y las horas trabajadas
        $intervalo = $fecha_ingreso_obj->diff($fecha_actual_obj);
        $dias_laborados = $intervalo->days;
        $horas_totales = $dias_laborados * 24 + $intervalo->h;

        // Establecer un límite de 8 horas  o 4 horas por día
        $horas_trabajadas = min($horas_totales, $dias_laborados * $tiempo_trabajo);

        // Incrementar 15 días en dias_totales_acu_user y dias_totales_vac_user por cada 365 días laborados
        // 8 horas de trabajo de un funcionario = 24 horas un dia
        $incremento_por_365_dias = 15;
        $cantidad_de_incrementos = floor($dias_laborados / 365);

        $dias_totales_acu_user = $cantidad_de_incrementos * $incremento_por_365_dias;
        $dias_totales_vac_user = $cantidad_de_incrementos * $incremento_por_365_dias;

        $consulta = "SELECT limiteVacaciones, diasPorAñoTrabajado, diasPorAño FROM dias_trabajo";
        $stmt = $pdo->prepare($consulta);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Asignar los valores a las variables
        $limiteVacaciones = $resultado["limiteVacaciones"];
        $diasPorAñoTrabajado = $resultado["diasPorAñoTrabajado"];
        $diasPorAño = $resultado["diasPorAño"];


        // Insertar el registro en la tabla dias_trabajados
        $queryInsert = "INSERT INTO dias_trabajo (id_usuarios, dias_laborados,horas_trabajadas, fecha_inicio, fecha_actual, dias_totales_acu_user, dias_totales_vac_user,limiteVacaciones,diasPorAñoTrabajado,diasPorAño ) VALUES (:id_usuarios, :dias_laborados,:horas_trabajadas,:fecha_inicio, :fecha_actual, :dias_totales_acu_user, :dias_totales_vac_user,:limiteVacaciones,:diasPorAnoTrabajado,:diasPorAno)";
        $statementInsert = $pdo->prepare($queryInsert);
        $statementInsert->bindParam(':id_usuarios', $id_usuario_insertado, PDO::PARAM_INT);
        $statementInsert->bindParam(':dias_laborados', $dias_laborados, PDO::PARAM_INT);
        $statementInsert->bindParam(':horas_trabajadas', $horas_trabajadas, PDO::PARAM_INT);
        $statementInsert->bindParam(':fecha_inicio', $fecha_ingreso_usuario, PDO::PARAM_STR);
        $statementInsert->bindValue(':fecha_actual', $fecha_actual_obj->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $statementInsert->bindParam(':dias_totales_acu_user', $dias_totales_acu_user, PDO::PARAM_STR);
        $statementInsert->bindParam(':dias_totales_vac_user', $dias_totales_vac_user, PDO::PARAM_STR);
        $statementInsert->bindParam(':limiteVacaciones', $limiteVacaciones, PDO::PARAM_STR);
        $statementInsert->bindParam(':diasPorAnoTrabajado', $diasPorAñoTrabajado, PDO::PARAM_STR);
        $statementInsert->bindParam(':diasPorAno', $diasPorAño, PDO::PARAM_STR);
        $statementInsert->execute();

        // Obtener el ID del último registro insertado
        $ultimoIdInsertado = $pdo->lastInsertId();

        // Retornar el ID del último registro insertado
        return $ultimoIdInsertado;

    } catch (PDOException $e) {
        return "Error de excepción: " . $e->getMessage();
    }
}
//funcion para actualiazar los dias y horas trabajados
function calcular_actualizar($pdo)
{
    try {
        // Comenzar la transacción
        $pdo->beginTransaction();

        $fecha_actual = date('Y-m-d'); // Fecha actual

        // Obtener las fechas de ingreso de todos los usuarios
        $query = "SELECT id_usuarios, fecha_ingreso, tiempo_trabajo FROM usuarios";
        $statement = $pdo->prepare($query);
        $statement->execute();

        // Iterar sobre los resultados y actualizar los registros en dias_trabajo
        while ($resultado = $statement->fetch(PDO::FETCH_ASSOC)) {
            $id_usuarios = $resultado['id_usuarios'];
            $fecha_ingreso_usuario = $resultado['fecha_ingreso'];
            $tiempo_trabajo = $resultado['tiempo_trabajo'];

            // Crear objetos DateTime para las fechas
            $fecha_actual_obj = new DateTime(date('Y-m-d'));
            $fecha_ingreso_obj = new DateTime($fecha_ingreso_usuario);

            // Calcular los días y las horas trabajadas
            $intervalo = $fecha_ingreso_obj->diff($fecha_actual_obj);
            $dias_laborados = $intervalo->days;
            $horas_totales = $dias_laborados * 24 + $intervalo->h;

            // Establecer un límite de 8 horas o 4 Horas por día
            $horas_trabajadas = min($horas_totales, $dias_laborados * $tiempo_trabajo);
            // Incrementar 15 días en dias_totales_acu_user y dias_totales_vac_user por cada 365 días laborados
            $incremento_por_365_dias = 15;
            $cantidad_de_incrementos = floor($dias_laborados / 365);

            $dias_totales_acu_user = $cantidad_de_incrementos * $incremento_por_365_dias;
            $dias_totales_vac_user = $cantidad_de_incrementos * $incremento_por_365_dias;

            // Actualizar el registro en la tabla dias_trabajo
            $calcular_act = "UPDATE dias_trabajo SET dias_laborados = :dias_laborados,horas_trabajadas = :horas_trabajadas, fecha_actual = :fecha_actual, dias_totales_acu_user = :dias_totales_acu_user, dias_totales_vac_user = :dias_totales_vac_user WHERE id_usuarios = :id_usuarios";

            // Preparo la consulta
            $stmt = $pdo->prepare($calcular_act);

            // Pasamos los parametros con sus valores
            $stmt->bindParam(':dias_laborados', $dias_laborados, PDO::PARAM_STR);
            $stmt->bindParam(':horas_trabajadas', $horas_trabajadas, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_actual', $fecha_actual, PDO::PARAM_STR);
            $stmt->bindParam(':dias_totales_acu_user', $dias_totales_acu_user, PDO::PARAM_INT);
            $stmt->bindParam(':dias_totales_vac_user', $dias_totales_vac_user, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuarios', $id_usuarios, PDO::PARAM_INT);

            // Ejecutamos la consulta con los parametros
            $stmt->execute();
        }

        // Confirmar la transacción si todo ha ido bien
        $pdo->commit();

        // Retorna verdadero para una futura comparación
        return true;
    } catch (PDOException $e) {
        // Deshacer la transacción en caso de error
        $pdo->rollBack();
        echo "Error de excepción: " . $e->getMessage();

        // Retorna falso en caso de un error para una futura comparación
        return false;
    }
}

function cons_table($pdo){
    try {
        $cons_table="SELECT usuarios.id_usuarios,registros_permisos.id_permisos,usuarios.nombres,usuarios.cedula,usuarios.apellidos,dias_trabajo.dias_laborados,dias_trabajo.horas_trabajadas,dias_trabajo.dias_totales_vac_user,dias_trabajo.dias_totales_acu_user,registros_permisos.permiso_aceptado,registros_permisos.dias_solicitados,registros_permisos.horas_solicitadas FROM usuarios,dias_trabajo,registros_permisos  WHERE usuarios.id_usuarios = dias_trabajo.id_usuarios AND usuarios.id_usuarios = registros_permisos.id_usuarios AND usuarios.rol = 'Funcionario'";
        $stmt = $pdo->prepare($cons_table);

        //Ejecutamos la consulta
        $stmt->execute();
        // Obtener los resultados
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultados;

    } catch (PDOException $e) {
        return "Error de exepcion" . $e->getMessage();
    }
}

//Funcion pra mostrar las solicitudes pendientes
function soli_no_aceptadas($pdo){
    try {
        $permiso_aceptado = 0;
        $soli_one = "SELECT registros_permisos.id_permisos,registros_permisos.id_usuarios,registros_permisos.fecha_permiso,registros_permisos.provincia,registros_permisos.regimen,usuarios.nombres,usuarios.apellidos,usuarios.cedula,registros_permisos.coordinacion_zonal,registros_permisos.direccion_unidad,registros_permisos.observaciones,registros_permisos.motivo_permiso,registros_permisos.tiempo_motivo,registros_permisos.fecha_permisos_desde,registros_permisos.fecha_permiso_hasta,registros_permisos.horas_permiso_desde,registros_permisos.horas_permiso_hasta,registros_permisos.dias_solicitados,registros_permisos.horas_solicitadas,registros_permisos.desc_motivo,registros_permisos.usuario_solicita,registros_permisos.usuario_aprueba,registros_permisos.usuario_registra,registros_permisos.desc_motivo,registros_permisos.permiso_aceptado FROM registros_permisos,usuarios WHERE usuarios.id_usuarios = registros_permisos.id_usuarios AND registros_permisos.permiso_aceptado = :permiso_aceptado ";
        $stmt = $pdo->prepare($soli_one);
        // $stmt->bindParam(':id_permisos',$id_permisos,PDO::PARAM_INT);
        $stmt->bindParam(':permiso_aceptado',$permiso_aceptado,PDO::PARAM_STR);
        $stmt->execute();
        $res_vista_permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res_vista_permisos;

    } catch (PDOException $e) {
        return "Error de exepcion" . $e->getMessage();
    }
}
function soli_aceptadas($pdo){
    try {
        $soli_one = "SELECT registros_permisos.id_permisos,registros_permisos.id_usuarios,registros_permisos.fecha_permiso,registros_permisos.provincia,registros_permisos.regimen,usuarios.nombres,usuarios.apellidos,usuarios.cedula,registros_permisos.coordinacion_zonal,registros_permisos.direccion_unidad,registros_permisos.observaciones,registros_permisos.motivo_permiso,registros_permisos.tiempo_motivo,registros_permisos.fecha_permisos_desde,registros_permisos.fecha_permiso_hasta,registros_permisos.horas_permiso_desde,registros_permisos.horas_permiso_hasta,registros_permisos.dias_solicitados,registros_permisos.horas_solicitadas,registros_permisos.desc_motivo,registros_permisos.usuario_solicita,registros_permisos.usuario_aprueba,registros_permisos.usuario_registra,registros_permisos.desc_motivo,registros_permisos.permiso_aceptado FROM registros_permisos,usuarios WHERE usuarios.id_usuarios = registros_permisos.id_usuarios AND (registros_permisos.permiso_aceptado = 1 OR registros_permisos.permiso_aceptado = 3) ";
        $stmt = $pdo->prepare($soli_one);
        // $stmt->bindParam(':id_permisos',$id_permisos,PDO::PARAM_INT);
        // $stmt->bindParam(':permiso_aceptado',$permiso_aceptado,PDO::PARAM_STR);
        $stmt->execute();
        $res_vista_permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res_vista_permisos;

    } catch (PDOException $e) {
        return "Error de exepcion" . $e->getMessage();
    }
}

function soli_rechazadas($pdo){
    try {
        $soli_one = "SELECT registros_permisos.id_permisos,registros_permisos.id_usuarios,registros_permisos.fecha_permiso,registros_permisos.provincia,registros_permisos.regimen,usuarios.nombres,usuarios.apellidos,usuarios.cedula,registros_permisos.coordinacion_zonal,registros_permisos.direccion_unidad,registros_permisos.observaciones,registros_permisos.motivo_permiso,registros_permisos.tiempo_motivo,registros_permisos.fecha_permisos_desde,registros_permisos.fecha_permiso_hasta,registros_permisos.horas_permiso_desde,registros_permisos.horas_permiso_hasta,registros_permisos.dias_solicitados,registros_permisos.horas_solicitadas,registros_permisos.desc_motivo,registros_permisos.usuario_solicita,registros_permisos.usuario_aprueba,registros_permisos.usuario_registra,registros_permisos.desc_motivo,registros_permisos.permiso_aceptado FROM registros_permisos,usuarios WHERE usuarios.id_usuarios = registros_permisos.id_usuarios AND (registros_permisos.permiso_aceptado = 2) ";
        $stmt = $pdo->prepare($soli_one);
        // $stmt->bindParam(':id_permisos',$id_permisos,PDO::PARAM_INT);
        // $stmt->bindParam(':permiso_aceptado',$permiso_aceptado,PDO::PARAM_STR);
        $stmt->execute();
        $res_vista_permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res_vista_permisos;

    } catch (PDOException $e) {
        return "Error de exepcion" . $e->getMessage();
    }
}

function permisosAprobados($pdo){
    try {
        $soli_one = "SELECT registros_permisos.id_permisos,registros_permisos.id_usuarios,registros_permisos.fecha_permiso,registros_permisos.provincia,registros_permisos.regimen,usuarios.nombres,usuarios.apellidos,usuarios.cedula,registros_permisos.coordinacion_zonal,registros_permisos.direccion_unidad,registros_permisos.observaciones,registros_permisos.motivo_permiso,registros_permisos.tiempo_motivo,registros_permisos.fecha_permisos_desde,registros_permisos.fecha_permiso_hasta,registros_permisos.horas_permiso_desde,registros_permisos.horas_permiso_hasta,registros_permisos.dias_solicitados,registros_permisos.horas_solicitadas,registros_permisos.desc_motivo,registros_permisos.usuario_solicita,registros_permisos.usuario_aprueba,registros_permisos.usuario_registra,registros_permisos.desc_motivo,registros_permisos.permiso_aceptado FROM registros_permisos,usuarios WHERE usuarios.id_usuarios = registros_permisos.id_usuarios AND registros_permisos.permiso_aceptado = 1 ";
        $stmt = $pdo->prepare($soli_one);
        // $stmt->bindParam(':id_permisos',$id_permisos,PDO::PARAM_INT);
        // $stmt->bindParam(':permiso_aceptado',$permiso_aceptado,PDO::PARAM_STR);
        $stmt->execute();
        $res_vista_permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res_vista_permisos;

    } catch (PDOException $e) {
        return "Error de exepcion" . $e->getMessage();
    }
}


function soli_registradas($pdo){
    try {
        $permiso_aceptado = 3;
        $soli_one = "SELECT registros_permisos.id_permisos,registros_permisos.id_usuarios,registros_permisos.fecha_permiso,registros_permisos.provincia,registros_permisos.regimen,usuarios.nombres,usuarios.apellidos,usuarios.cedula,registros_permisos.coordinacion_zonal,registros_permisos.direccion_unidad,registros_permisos.observaciones,registros_permisos.motivo_permiso,registros_permisos.tiempo_motivo,registros_permisos.fecha_permisos_desde,registros_permisos.fecha_permiso_hasta,registros_permisos.horas_permiso_desde,registros_permisos.horas_permiso_hasta,registros_permisos.dias_solicitados,registros_permisos.horas_solicitadas,registros_permisos.desc_motivo,registros_permisos.usuario_solicita,registros_permisos.usuario_aprueba,registros_permisos.usuario_registra,registros_permisos.desc_motivo,registros_permisos.permiso_aceptado FROM registros_permisos,usuarios WHERE usuarios.id_usuarios = registros_permisos.id_usuarios AND registros_permisos.permiso_aceptado = :permiso_aceptado ";
        $stmt = $pdo->prepare($soli_one);
        // $stmt->bindParam(':id_permisos',$id_permisos,PDO::PARAM_INT);
        $stmt->bindParam(':permiso_aceptado',$permiso_aceptado,PDO::PARAM_STR);
        $stmt->execute();
        $res_vista_permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res_vista_permisos;

    } catch (PDOException $e) {
        return "Error de exepcion" . $e->getMessage();
    }
}


//Funcion para iterar cedulas con nombres
function cedulas($pdo){
    try {

        $rol = 'Funcionario';
        $cedulas = "SELECT id_usuarios,cedula,nombres,apellidos FROM usuarios WHERE rol =:rol";
        $stmt = $pdo->prepare($cedulas);
        $stmt->bindParam(':rol',$rol, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultado;

    } catch (PDOException $e) {
        return "Error de exepcion" . $e->getMessage();
    }
}
//Cedulas con registros en la tabla registrsos_permisos
function cedulasConSoli($pdo){
    try {

        $rol = 'Funcionario';
        $cedulas = "SELECT id_usuarios, cedula, nombres, apellidos
        FROM usuarios
        WHERE rol = :rol
        AND id_usuarios IN (SELECT DISTINCT id_usuarios FROM registros_permisos)";
        $stmt = $pdo->prepare($cedulas);
        $stmt->bindParam(':rol',$rol, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultado;

    } catch (PDOException $e) {
        return "Error de exepcion" . $e->getMessage();
    }
}
//Funcion para ver la solicitud en especifico de un solo funcionario
function vista_unica($id_permisos,$pdo){
    try {
        $soli_one = "SELECT registros_permisos.id_permisos,registros_permisos.id_usuarios,registros_permisos.fecha_permiso,registros_permisos.provincia,registros_permisos.regimen,usuarios.nombres,usuarios.apellidos,usuarios.cedula,registros_permisos.coordinacion_zonal,registros_permisos.direccion_unidad,registros_permisos.observaciones,registros_permisos.motivo_permiso,registros_permisos.tiempo_motivo,registros_permisos.fecha_permisos_desde,registros_permisos.fecha_permiso_hasta,registros_permisos.horas_permiso_desde,registros_permisos.horas_permiso_hasta,registros_permisos.dias_solicitados,registros_permisos.horas_solicitadas,registros_permisos.desc_motivo,registros_permisos.usuario_solicita,registros_permisos.usuario_aprueba,registros_permisos.usuario_registra,registros_permisos.desc_motivo,registros_permisos.permiso_aceptado FROM registros_permisos,usuarios WHERE usuarios.id_usuarios = registros_permisos.id_usuarios AND registros_permisos.id_permisos= :id_permisos";
        $stmt = $pdo->prepare($soli_one);
        $stmt->bindParam(':id_permisos',$id_permisos,PDO::PARAM_INT);
        $stmt->execute();
        $res_vista_permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res_vista_permisos;

    } catch (PDOException $e) {
        return "Error de exepcion" . $e->getMessage();
    }
}


function eliminar_permiso($pdo,$id_permisos){
    try {
        $delete_permiso = "DELETE FROM registros_permisos WHERE id_permisos=:id_permisos";

        $stmt = $pdo->prepare($delete_permiso);

        $stmt->bindParam(':id_permisos',$id_permisos, PDO::PARAM_INT);

        $stmt->execute();

        create_flash_message(
            'Permiso Eliminado Correctamente',
            'success'
        );

        redirect(RUTA_ABSOLUTA . "admin/solicitud_general");
    } catch (PDOExeception $e) {
        echo "Error de exepcion" . $e->getMessage();
    }
}
?>