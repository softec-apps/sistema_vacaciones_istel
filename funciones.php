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
            if (password_verify($pass_c,$password_bd)) {
                get_session();
                $_SESSION['id_usuarios'] = $info['id_usuarios'];
                $_SESSION['cedula'] = $info['cedula'];
                $_SESSION['nombres'] = $info['nombres'];
                $_SESSION['apellidos'] = $info['apellidos'];
                $_SESSION['rol'] = $info['rol'];
                $_SESSION['fecha_ingreso'] = $info['fecha_ingreso'];

                include_once "actualizar_diasTrabajados.php";
                if ($rol == ROL_FUNCIONARIO) {
                    create_flash_message(
                        $mensaje,
                        'success'
                    );

                    redirect("funcionario/dashboard");

                }elseif ($rol == ROL_JEFE) {
                    create_flash_message(
                        $mensaje,
                        'success'
                    );

                    redirect("jefe/dashboard");

                }elseif ($rol == ROL_ADMIN) {

                    create_flash_message(
                        $mensaje,
                        'success'
                    );

                    redirect("admin/dashboard");

                }elseif ($rol == ROL_TALENTO_HUMANO) {
                    create_flash_message(
                        $mensaje,
                        'success'
                    );

                    redirect("talento_h/dashboard");

                }else {
                    create_flash_message(
                        $mensaje,
                        'success'
                    );
                    redirect(RUTA_ABSOLUTA . "inicio");
                }
            }else{

                create_flash_message(
                    'La contraseña no coincide',
                    'error'
                );
                redirect(RUTA_ABSOLUTA . "inicio");
            }
        } else {
            create_flash_message(
                'Error en las credenciales',
                'error'
            );
            redirect(RUTA_ABSOLUTA . "inicio");
        }

    } catch (PDOException $e) {
        return "Error de exención" .$e->getMessage();
    }finally {

        $pdo = null;
    }
}
//Insertar datos de un usuario
function insertUsers($cedula, $nombres, $apellidos, $email, $usuario, $clave, $rol, $fecha_ingreso,$tiempo_trabajo, $pdo)
{
    try {

        // Comenzar la transacción
        $pdo->beginTransaction();

        $nuevoPassword=password_hash($clave, PASSWORD_DEFAULT);
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
            ':clave' => $nuevoPassword,
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
    }finally {

        $pdo = null;
    }
}

//Mostrar Datos de un Usuario
function mostrarUsuarios($pdo,$id){
    try {

        // $id=1;
        $consuta_usuarios = "SELECT id_usuarios,cedula,nombres,apellidos,usuario,contraseña,email,rol,fecha_ingreso,tiempo_trabajo FROM usuarios WHERE id_usuarios <> :id_usuarios AND rol != 'admin'";
        //Premaramos la consulta
        $stmt = $pdo->prepare($consuta_usuarios);

        //Ejecutamos la consulta
        $stmt->execute([':id_usuarios'=>$id]);
        // Obtener los resultados
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultados;

    }catch (PDOException $e) {
        return "Error de exención" .$e->getMessage();
    }finally {

        $pdo = null;
    }
}
//Actualizar datos de un usuario
function actualizar_usuario($pdo,$id_usuarios,$cedula,$nombres,$apellidos,$usuario,$email,$rol,$fecha_ingreso,$tiempo_trabajo){

    try {

        $act_user = "UPDATE usuarios SET cedula=:cedula,nombres=:nombres,apellidos=:apellidos,email=:email,usuario=:usuario,rol=:rol,fecha_ingreso=:fecha_ingreso,tiempo_trabajo=:tiempo_trabajo WHERE id_usuarios =:id_usuarios";
        //Preparamos la consulta
        $stmt = $pdo->prepare($act_user);

        //Parametros con sus valores
        $stmt->bindParam(':id_usuarios',$id_usuarios,PDO::PARAM_INT);
        $stmt->bindParam(':cedula',$cedula,PDO::PARAM_STR);
        $stmt->bindParam(':nombres',$nombres,PDO::PARAM_STR);
        $stmt->bindParam(':apellidos',$apellidos,PDO::PARAM_STR);
        $stmt->bindParam(':email',$email,PDO::PARAM_STR);
        $stmt->bindParam(':usuario',$usuario,PDO::PARAM_STR);
        $stmt->bindParam(':rol',$rol,PDO::PARAM_STR);
        $stmt->bindParam(':fecha_ingreso',$fecha_ingreso,PDO::PARAM_STR);
        $stmt->bindParam(':tiempo_trabajo',$tiempo_trabajo,PDO::PARAM_INT);

        //Ejecutamos la consulta con los parametros
        $stmt->execute();
        return "Datos Actualizados";
    } catch (PDOException $e) {

        // Capturar el código de error
        $errorCode = $e->getCode();

        if ($errorCode == '23000') {
            return "Error: Violación de clave única.";
        }

        return "Error de exepcion" .$e->getMessage();
    }finally {

        $pdo = null;
    }

}


//Recuperar contraseña de un usuario
function recuperarClave($pdo,$id_usuarios,$clave){
    try {

        $nuevoPassword=password_hash($clave, PASSWORD_DEFAULT);
        $act_user = "UPDATE usuarios SET contraseña=:clave WHERE id_usuarios =:id_usuarios";
        //Preparamos la consulta
        $stmt = $pdo->prepare($act_user);

        //Parametros con sus valores
        $stmt->bindParam(':id_usuarios',$id_usuarios,PDO::PARAM_INT);
        $stmt->bindParam(':clave',$nuevoPassword,PDO::PARAM_STR);

        //Ejecutamos la consulta con los parametros
        $stmt->execute();
        return "Datos Actualizados";
    } catch (PDOException $e) {
        return "Error de excepción" .$e->getMessage();
    }finally {

        $pdo = null;
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
        return "Error de exepcion" .$e->getMessage();
    }finally {

        $pdo = null;
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
        return "Error de exención" .$e->getMessage();
    }finally {

        $pdo = null;
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


        // Insertar el registro en la tabla dias_trabajados
        $queryInsert = "INSERT INTO dias_trabajo (id_usuarios, dias_laborados,horas_trabajadas, fecha_inicio, fecha_actual, dias_totales_acu_user, dias_totales_vac_user) VALUES (:id_usuarios, :dias_laborados,:horas_trabajadas,:fecha_inicio, :fecha_actual, :dias_totales_acu_user, :dias_totales_vac_user)";
        $statementInsert = $pdo->prepare($queryInsert);
        $statementInsert->bindParam(':id_usuarios', $id_usuario_insertado, PDO::PARAM_INT);
        $statementInsert->bindParam(':dias_laborados', $dias_laborados, PDO::PARAM_INT);
        $statementInsert->bindParam(':horas_trabajadas', $horas_trabajadas, PDO::PARAM_INT);
        $statementInsert->bindParam(':fecha_inicio', $fecha_ingreso_usuario, PDO::PARAM_STR);
        $statementInsert->bindValue(':fecha_actual', $fecha_actual_obj->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $statementInsert->bindParam(':dias_totales_acu_user', $dias_totales_acu_user, PDO::PARAM_STR);
        $statementInsert->bindParam(':dias_totales_vac_user', $dias_totales_vac_user, PDO::PARAM_STR);
        $statementInsert->execute();

        // Obtener el ID del último registro insertado
        $ultimoIdInsertado = $pdo->lastInsertId();

        // Retornar el ID del último registro insertado
        return $ultimoIdInsertado;

    } catch (PDOException $e) {
        return "Error de excepción: " . $e->getMessage();
    }finally {

        $pdo = null;
    }
}
//funcion para actualizar los dias y horas trabajados
function calcular_actualizar($pdo,$dias_laborados,$horas_trabajadas,$dias_totales_acu_user,$dias_totales_vac_user,$id_usuarios){
    try {

        // Actualizar el registro en la tabla dias_trabajo
        $calcular_act = "UPDATE dias_trabajo SET dias_laborados = :dias_laborados,horas_trabajadas = :horas_trabajadas, dias_totales_acu_user = :dias_totales_acu_user, dias_totales_vac_user = :dias_totales_vac_user WHERE id_usuarios = :id_usuarios";

        // Preparo la consulta
        $stmt = $pdo->prepare($calcular_act);

        // Pasamos los parametros con sus valores
        $stmt->bindParam(':dias_laborados', $dias_laborados, PDO::PARAM_STR);
        $stmt->bindParam(':horas_trabajadas', $horas_trabajadas, PDO::PARAM_STR);
        $stmt->bindParam(':dias_totales_acu_user', $dias_totales_acu_user, PDO::PARAM_INT);
        $stmt->bindParam(':dias_totales_vac_user', $dias_totales_vac_user, PDO::PARAM_STR);
        $stmt->bindParam(':id_usuarios', $id_usuarios, PDO::PARAM_INT);

        // Ejecutamos la consulta con los parametros
        $stmt->execute();


    } catch (PDOException $e) {

        return "Error de excepción: " . $e->getMessage();
    }finally {

        $pdo = null;
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
    }finally {

        $pdo = null;
    }
}

//Funcion pra mostrar las solicitudes pendientes
function soli_no_aceptadas($pdo){
    try {
        $permiso_aceptado = 0;
        $soli_one = "SELECT registros_permisos.id_permisos,registros_permisos.id_usuarios,registros_permisos.fecha_permiso,registros_permisos.provincia,registros_permisos.regimen,usuarios.nombres,usuarios.apellidos,usuarios.cedula,registros_permisos.coordinacion_zonal,registros_permisos.direccion_unidad,registros_permisos.observaciones,registros_permisos.motivo_permiso,registros_permisos.tiempo_motivo,registros_permisos.fecha_permisos_desde,registros_permisos.fecha_permiso_hasta,registros_permisos.horas_permiso_desde,registros_permisos.horas_permiso_hasta,registros_permisos.dias_solicitados,registros_permisos.horas_solicitadas,registros_permisos.desc_motivo,registros_permisos.usuario_solicita,registros_permisos.usuario_aprueba,registros_permisos.usuario_registra,registros_permisos.desc_motivo,registros_permisos.permiso_aceptado,registros_permisos.ruta_solicita,registros_permisos.ruta_aprueba,registros_permisos.ruta_registra FROM registros_permisos,usuarios WHERE usuarios.id_usuarios = registros_permisos.id_usuarios AND registros_permisos.permiso_aceptado = :permiso_aceptado AND  COALESCE(registros_permisos.ruta_solicita, '') != '' ";
        $stmt = $pdo->prepare($soli_one);
        // $stmt->bindParam(':id_permisos',$id_permisos,PDO::PARAM_INT);
        $stmt->bindParam(':permiso_aceptado',$permiso_aceptado,PDO::PARAM_STR);
        $stmt->execute();
        $res_vista_permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res_vista_permisos;

    } catch (PDOException $e) {
        return "Error de exepcion" . $e->getMessage();
    }finally {

        $pdo = null;
    }
}
function soli_aceptadas($pdo){
    try {
        $soli_one = "SELECT registros_permisos.id_permisos,registros_permisos.id_usuarios,registros_permisos.fecha_permiso,registros_permisos.provincia,registros_permisos.regimen,usuarios.nombres,usuarios.apellidos,usuarios.cedula,registros_permisos.coordinacion_zonal,registros_permisos.direccion_unidad,registros_permisos.observaciones,registros_permisos.motivo_permiso,registros_permisos.tiempo_motivo,registros_permisos.fecha_permisos_desde,registros_permisos.fecha_permiso_hasta,registros_permisos.horas_permiso_desde,registros_permisos.horas_permiso_hasta,registros_permisos.dias_solicitados,registros_permisos.horas_solicitadas,registros_permisos.desc_motivo,registros_permisos.usuario_solicita,registros_permisos.usuario_aprueba,registros_permisos.usuario_registra,registros_permisos.desc_motivo,registros_permisos.permiso_aceptado FROM registros_permisos,usuarios WHERE usuarios.id_usuarios = registros_permisos.id_usuarios AND (registros_permisos.permiso_aceptado = 1) ";
        $stmt = $pdo->prepare($soli_one);
        $stmt->execute();
        $res_vista_permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res_vista_permisos;

    } catch (PDOException $e) {
        return "Error de exepcion" . $e->getMessage();
    }finally {

        $pdo = null;
    }
}

function soliArchivosAceptadas($pdo){
    try {
        $soli_one = "SELECT registros_permisos.id_permisos, registros_permisos.fecha_permiso, usuarios.nombres, usuarios.apellidos, usuarios.cedula, registros_permisos.motivo_permiso, registros_permisos.permiso_aceptado, archivos.ruta_solicita, archivos.ruta_aprueba
        FROM registros_permisos
        JOIN usuarios ON usuarios.id_usuarios = registros_permisos.id_usuarios
        LEFT JOIN archivos ON archivos.id_permiso = registros_permisos.id_permisos
        WHERE registros_permisos.permiso_aceptado = 1";
        $stmt = $pdo->prepare($soli_one);
        $stmt->execute();
        $res_vista_permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res_vista_permisos;

    } catch (PDOException $e) {
        return "Error de exepcion" . $e->getMessage();
    }finally {

        $pdo = null;
    }
}

function verificarRuta($ruta)
{
    $carpeta_permitida = "htArchivos";
    $posicion = strpos($ruta, $carpeta_permitida);

    if ($posicion !== false) {
        return substr($ruta, $posicion);
    } else {
        return "error";
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
    }finally {

        $pdo = null;
    }
}

function permisosAprobados($pdo){
    try {
        $soli_one = "SELECT registros_permisos.id_permisos,registros_permisos.id_usuarios,registros_permisos.fecha_permiso,usuarios.nombres,usuarios.apellidos,usuarios.cedula,registros_permisos.ruta_aprueba,registros_permisos.motivo_permiso,registros_permisos.permiso_aceptado FROM registros_permisos,usuarios WHERE usuarios.id_usuarios = registros_permisos.id_usuarios AND registros_permisos.permiso_aceptado = 1 ";
        $stmt = $pdo->prepare($soli_one);
        // $stmt->bindParam(':id_permisos',$id_permisos,PDO::PARAM_INT);
        // $stmt->bindParam(':permiso_aceptado',$permiso_aceptado,PDO::PARAM_STR);
        $stmt->execute();
        $res_vista_permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res_vista_permisos;

    } catch (PDOException $e) {
        return "Error de exepcion" . $e->getMessage();
    }finally {

        $pdo = null;
    }
}


function soli_registradas($pdo){
    try {
        $permiso_aceptado = 3;
        $soli_one = "SELECT registros_permisos.id_permisos,registros_permisos.id_usuarios,registros_permisos.fecha_permiso,usuarios.nombres,usuarios.apellidos,usuarios.cedula,registros_permisos.motivo_permiso,registros_permisos.permiso_aceptado FROM registros_permisos,usuarios WHERE usuarios.id_usuarios = registros_permisos.id_usuarios AND registros_permisos.permiso_aceptado = :permiso_aceptado ";
        $stmt = $pdo->prepare($soli_one);
        // $stmt->bindParam(':id_permisos',$id_permisos,PDO::PARAM_INT);
        $stmt->bindParam(':permiso_aceptado',$permiso_aceptado,PDO::PARAM_STR);
        $stmt->execute();
        $res_vista_permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res_vista_permisos;

    } catch (PDOException $e) {
        return "Error de exepcion" . $e->getMessage();
    }finally {

        $pdo = null;
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
    }finally {

        $pdo = null;
    }
}

//Funcion para Cargar todos los usauarios con excepcion de los administradores
function sinAdmin($pdo){
    try {

        $rol = 'admin';
        $cedulas = "SELECT id_usuarios,cedula,nombres,apellidos FROM usuarios WHERE rol !=:rol AND rol !='Funcionario'";
        $stmt = $pdo->prepare($cedulas);
        $stmt->bindParam(':rol',$rol, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultado;

    } catch (PDOException $e) {
        return null;
    }finally {

        $pdo = null;
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
    }finally {

        $pdo = null;
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
        return "Error de excepción: " . $e->getMessage();
    }finally {

        $pdo = null;
    }
}


function eliminar_permiso($pdo,$id_permisos){
    try {
        $delete_permiso = "DELETE FROM registros_permisos WHERE id_permisos=:id_permisos AND COALESCE(ruta_solicita, '') = '' AND  COALESCE(ruta_aprueba, '') = '' AND  COALESCE(ruta_registra, '') = ''"  ;

        $stmt = $pdo->prepare($delete_permiso);

        $stmt->bindParam(':id_permisos',$id_permisos, PDO::PARAM_INT);

        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        return "Error de excepción: " . $e->getMessage();
    }finally {

        $pdo = null;
    }
}

function contar($pdo){
    try {
        $rol = "funcionario";
        $con = "SELECT COUNT(*) as total FROM usuarios WHERE rol = :funcionario";
        $stmt = $pdo->prepare($con);

        $stmt->bindParam(':funcionario',$rol,PDO::PARAM_STR);
        $stmt->execute();
        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Cerrar la conexión
        $pdo = null;
        return $res_vista;

    } catch (PDOException $e) {
        return "Error de excepción: " . $e->getMessage();
    }
}

function countAceptados($pdo){
    try {
        $con = "SELECT COUNT(*) as total FROM registros_permisos WHERE permiso_aceptado = 1";
        $stmt = $pdo->prepare($con);

        $stmt->execute();
        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Cerrar la conexión
        $pdo = null;
        return $res_vista;

    } catch (PDOException $e) {
        return "Error de excepción: " . $e->getMessage();
    }
}

function countRegistrados($pdo){
    try {
        $con = "SELECT COUNT(*) as total FROM registros_permisos WHERE permiso_aceptado = 3";
        $stmt = $pdo->prepare($con);

        $stmt->execute();
        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Cerrar la conexión
        $pdo = null;
        return $res_vista;

    } catch (PDOException $e) {
        return "Error de excepción: " . $e->getMessage();
    }
}

function seleccionarConfi($pdo){
    try {
        $consulta = "SELECT limiteVacaciones, diasPorAño, diasAnuales, numero FROM configuracion";
        $stmt = $pdo->prepare($consulta);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Asignar los valores a las variables
        $limiteVacaciones = $resultado["limiteVacaciones"];
        $diasPorAnoTrabajado = $resultado["diasPorAño"];
        $diasPorAno = $resultado["diasAnuales"];
        $numero = $resultado["numero"];

        // Puedes devolver las variables si es necesario
        return [
            'limiteVacaciones' => $limiteVacaciones,
            'diasPorAnoTrabajado' => $diasPorAnoTrabajado,
            'diasPorAno' => $diasPorAno,
            'numero' => $numero,
        ];
    } catch (PDOException $e) {
        return "Error de excepción: " . $e->getMessage();
    }finally {

        $pdo = null;
    }
}

function onceMeses($pdo, $id_usuario_insertado) {
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

        // Calcular el intervalo de tiempo
        $intervalo = $fecha_ingreso_obj->diff($fecha_actual_obj);

        // Obtener años, meses y días por separado
        $anios = $intervalo->y;
        $meses = $intervalo->m;

        // Calcular el número total de meses y días transcurridos
        $mesesTotales = $anios * 12 + $meses;

        // Construir el mensaje
        $mensaje = $mesesTotales;

        // Retornar el mensaje
        return $mensaje;

    } catch (PDOException $e) {
        return "Error de excepción: " . $e->getMessage();
    }finally {

        $pdo = null;
    }
}

function obtenerAnioMesActual() {
    $anio = date("Y");
    $mes = date("m");

    return array('anio' => $anio, 'mes' => $mes);
}

// Función para quitar acentos y caracteres especiales
function quitarAcentos($cadena) {
    $cadena = strtr(utf8_decode($cadena), utf8_decode('áéíóúüñÁÉÍÓÚÜÑ'), 'aeiouunAEIOUUN');
    return utf8_encode($cadena);
}

function archivos_individuales($pdo,$id){
    try {
        $con = "SELECT usuarios.id_usuarios,usuarios.nombres,usuarios.apellidos,usuarios.cedula,registros_permisos.id_permisos,archivos.id_archivo,archivos.ruta_solicita,archivos.ruta_aprueba,archivos.ruta_registra,registros_permisos.motivo_permiso FROM usuarios JOIN registros_permisos ON usuarios.id_usuarios = registros_permisos.id_usuarios JOIN archivos ON registros_permisos.id_permisos = archivos.id_permiso WHERE usuarios.id_usuarios = :id_usuarios";
        $stmt = $pdo->prepare($con);

        $stmt->bindParam(':id_usuarios',$id,PDO::PARAM_INT);
        $stmt->execute();
        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Cerrar la conexión
        $pdo = null;
        return $res_vista;

    } catch (PDOException $e) {
        return "Error de excepción: " . $e->getMessage();
    }
}

function archivosJefe($pdo,$id_usuario,$id_aprueba){
    try {
        $con = "SELECT usuarios.id_usuarios,usuarios.nombres,usuarios.apellidos,usuarios.cedula,registros_permisos.id_permisos,archivos.id_archivo,archivos.ruta_solicita,archivos.ruta_aprueba,archivos.ruta_registra,registros_permisos.motivo_permiso FROM usuarios JOIN registros_permisos ON usuarios.id_usuarios = registros_permisos.id_usuarios JOIN archivos ON registros_permisos.id_permisos = archivos.id_permiso WHERE
        usuarios.id_usuarios = :id_usuarios AND archivos.id_aprueba = :id_aprueba ";
        $stmt = $pdo->prepare($con);

        $stmt->bindParam(':id_usuarios',$id_usuario,PDO::PARAM_INT);
        $stmt->bindParam(':id_aprueba',$id_aprueba,PDO::PARAM_INT);
        $stmt->execute();
        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Cerrar la conexión
        $pdo = null;
        return $res_vista;

    } catch (PDOException $e) {
        return "Error de excepción: " . $e->getMessage();
    }
}

function archivos_talentoHumano($pdo,$id_usuario,$id_registra){
    try {
        $con = "SELECT usuarios.id_usuarios,usuarios.nombres,usuarios.apellidos,usuarios.cedula,registros_permisos.id_permisos,archivos.id_archivo,archivos.ruta_solicita,archivos.ruta_aprueba,archivos.ruta_registra,registros_permisos.motivo_permiso FROM usuarios JOIN registros_permisos ON usuarios.id_usuarios = registros_permisos.id_usuarios JOIN archivos ON registros_permisos.id_permisos = archivos.id_permiso WHERE
        usuarios.id_usuarios = :id_usuarios AND archivos.id_registra = :id_registra ";
        $stmt = $pdo->prepare($con);

        $stmt->bindParam(':id_usuarios',$id_usuario,PDO::PARAM_INT);
        $stmt->bindParam(':id_registra',$id_registra,PDO::PARAM_INT);
        $stmt->execute();
        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Cerrar la conexión
        $pdo = null;
        return $res_vista;

    } catch (PDOException $e) {
        return "Error de excepción: " . $e->getMessage();
    }
}

function vista1($pdo){
    try {

        $con = "SELECT * FROM vista1";

        $stmt = $pdo->prepare($con);

        $stmt->execute();

        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdo = null;

        return $res_vista;

    } catch (PDOException $e) {
        return "Error de excepción: " . $e->getMessage();
    }
}

function vista2($pdo,$id){
    try {

        $con = "SELECT DISTINCT * FROM vista2 WHERE id_registra = :id";

        $stmt = $pdo->prepare($con);
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);

        $stmt->execute();

        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdo = null;

        return $res_vista;

    } catch (PDOException $e) {
        return "Error de excepción: " . $e->getMessage();
    }
}

function vista3($pdo,$id){
    try {

        $con = "SELECT DISTINCT * FROM vista3 WHERE id_aprueba = :id";

        $stmt = $pdo->prepare($con);
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);

        $stmt->execute();

        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdo = null;

        return $res_vista;

    } catch (PDOException $e) {
        return "Error de excepción: " . $e->getMessage();
    }
}

function vista4($pdo,$id){
    try {

        $con = "SELECT * FROM permisosregistrados WHERE id_registra = :id";

        $stmt = $pdo->prepare($con);
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);

        $stmt->execute();

        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdo = null;

        return $res_vista;

    } catch (PDOException $e) {
        return "Error de excepción: " . $e->getMessage();
    }
}


function archivosDelFuncionarioAdmin($pdo,$id){

    try {
        $con = "SELECT registros_permisos.id_permisos,archivos.id_archivo,archivos.ruta_solicita,registros_permisos.motivo_permiso,usuarios.nombres,archivos.descripcion_solicita FROM registros_permisos, archivos,usuarios WHERE usuarios.id_usuarios = registros_permisos.id_usuarios AND usuarios.id_usuarios = :id_usuarios AND registros_permisos.id_permisos  = archivos.id_permiso AND COALESCE(registros_permisos.ruta_solicita, '') != '' ";
        $stmt = $pdo->prepare($con);
        $stmt->bindParam(':id_usuarios',$id,PDO::PARAM_INT);

        $stmt->execute();
        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo = null;
        return $res_vista;

    } catch (PDOException $e) {

        return "Error de excepción: " . $e->getMessage();

    }
}


function datosdeArchivosDelJefe($pdo,$id){
    try {
        $con = "SELECT id_archivo,id_permiso,id_aprueba,descripcion_aprueba,ruta_aprueba FROM archivos WHERE id_aprueba=:id_usuario AND COALESCE(ruta_aprueba, '') != '' ";
        $stmt = $pdo->prepare($con);

        $stmt->bindParam(':id_usuario',$id,PDO::PARAM_INT);

        $stmt->execute();
        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdo = null;
        return $res_vista;

    } catch (PDOException $e) {
        return "Error de excepción: " . $e->getMessage();
    }
}


function archivosAprobadosAdmin($pdo,$id){

    try {
        $con = "SELECT registros_permisos.id_permisos,archivos.id_archivo,archivos.ruta_aprueba,registros_permisos.motivo_permiso,usuarios.nombres,archivos.descripcion_aprueba FROM registros_permisos, archivos,usuarios WHERE usuarios.id_usuarios = registros_permisos.id_usuarios AND usuarios.id_usuarios = :id_usuarios AND registros_permisos.id_permisos  = archivos.id_permiso AND COALESCE(registros_permisos.ruta_aprueba, '') != '' ";
        $stmt = $pdo->prepare($con);
        $stmt->bindParam(':id_usuarios',$id,PDO::PARAM_INT);

        $stmt->execute();
        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo = null;
        return $res_vista;

    } catch (PDOException $e) {

        return "Error de excepción: " . $e->getMessage();

    }
}

function archivosRegistradosAdmin($pdo,$id){
    try {
        $con = "SELECT registros_permisos.id_permisos,archivos.id_archivo,archivos.ruta_registra,registros_permisos.motivo_permiso,usuarios.nombres,archivos.descripcion_registra FROM registros_permisos, archivos,usuarios WHERE usuarios.id_usuarios = registros_permisos.id_usuarios AND usuarios.id_usuarios = :id_usuarios AND registros_permisos.id_permisos  = archivos.id_permiso AND COALESCE(registros_permisos.ruta_registra, '') != ''";
        $stmt = $pdo->prepare($con);

        $stmt->bindParam(':id_usuarios',$id,PDO::PARAM_INT);
        $stmt->execute();
        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdo = null;
        return $res_vista;

    } catch (PDOException $e) {
        return "Error de excepción: " . $e->getMessage();
    }
}

function archivosRegistradosTH($pdo,$id){
    try {
        $con = "SELECT id_archivo,id_permiso,id_registra,descripcion_registra,ruta_registra FROM `archivos` WHERE id_registra =:id_usuarios";
        $stmt = $pdo->prepare($con);

        $stmt->bindParam(':id_usuarios',$id,PDO::PARAM_INT);

        $stmt->execute();
        $res_vista = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdo = null;
        return $res_vista;

    } catch (PDOException $e) {
        return "Error de excepción: " . $e->getMessage();
    }
}
?>