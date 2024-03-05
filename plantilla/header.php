
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php
    echo $titulo;
    ?>
    </title>
<?php
include_once "../redirection.php";
if (!isset($_SESSION['id_usuarios'])) {
    redirect(RUTA_ABSOLUTA . 'logout');
}
$cedula = $_SESSION['cedula'];
$nombre = $_SESSION['nombres'];
$rol = $_SESSION['rol'];
?>
    <link rel="stylesheet" href="<?php echo RUTA_ABSOLUTA; ?>css/personal/styles.css">
    <!-- Script Js -->
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Script de flash_messages -->
    <script src="../js/flash_messages.js"></script>
    <!-- CDN de bootstrap 5  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- CSS DATATABLES -->
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <!-- Js datatables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <!-- Script fontawesome-free -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- CDN botones datatables -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <!-- Script JSZip -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <!-- CDN Botones Datatables -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <!-- CDN select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Custom fonts for this template-->

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Estilos del formulario -->
    <link rel="stylesheet" href="<?php echo RUTA_ABSOLUTA; ?>css/personal/estilo_imprimir.css" />
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <link href="https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet"></link>
    <!-- Custom styles for this template-->
    <link href="<?php echo RUTA_ABSOLUTA; ?>css/sb-admin-2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo RUTA_ABSOLUTA; ?>css/personal/estilos_personales.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <!-- CDN Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- cdn cahrt json_decode -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>

<body class="sb-nav-fixed">
    <!-- Page Wrapper -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark topbar superior" id="navegacion_2">
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0 ms-0 rounded-5 toggle" id="sidebarToggle" href="#!"><i class="fas fa-bars icono"></i></button>
        <!-- Navbar Brand-->
        <p class=" fa-lg ps-2 pe-5 me-5 mb-0 texto">Sistema permisos</p>
        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto" id="datos">
            <div class="topbar-divider d-none d-sm-block"></div>
            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow ">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-white"><?php echo $nombre; ?></span>
                    <img class="img-profile rounded-circle" src="<?php echo RUTA_ABSOLUTA; ?>imagenes_defecto/descarga.jpg">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <?php
                         if($rol == 'admin') { ?>
                    <a class="dropdown-item" href="<?php echo RUTA_ABSOLUTA; ?>configuracion/configuracionAcumulados">
                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                        Configuración
                    </a>
                    <?php }?>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i>
                        Cerrar Sesión
                    </a>
                </div>
            </li>

        </ul>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion superior " id="sidenavAccordion">
                <div class="sb-sidenav-menu ">
                    <div class="nav">
                        <!-- <div class="sb-sidenav-menu-heading">Panel</div> -->

                        <a class="nav-link texto rounded-5 mt-3" href="
                        <?php
                        switch ($rol) {
                            case ROL_ADMIN:

                                echo RUTA_ABSOLUTA . "admin/dashboard";

                                break;
                            case ROL_JEFE:

                                echo RUTA_ABSOLUTA . "jefe/dashboard";

                                break;
                            case ROL_TALENTO_HUMANO:


                                echo RUTA_ABSOLUTA . "talento_h/dashboard";

                                break;
                            case ROL_FUNCIONARIO:

                                echo RUTA_ABSOLUTA . "funcionario/dashboard";

                                break;
                            default:
                            echo RUTA_ABSOLUTA . "logout";
                                break;
                        }
                        ?>">
                            <div class="sb-nav-link-icon"><i class="fa-lg fas fa-tachometer-alt"></i></div>
                            Inicio
                        </a>
                        <?php
                         if($rol == 'admin') { ?>
                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>admin/admin">
                            <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-user-secret"></i></div>
                            Agregar usuarios
                        </a>

                        <!-- <form action="admin/calcular" method="post" > -->
                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>admin/trabajo">
                            <!-- <input type="hidden" name="id_usuario" value=""> -->
                            <!-- <button class="nav-link texto text-left rounded-5" type="submit"> -->
                            <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-calendar"></i></div>
                            Funcionarios</button>
                        </a>
                        <!-- </form> -->

                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>admin/solicitud_general">
                        <div class="sb-nav-link-icon"><i class="fa-lg bi bi-envelope"></i></div>
                        Solicitar permiso
                        </a>

                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>admin/archivosSubidos">
                        <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-folder-open"></i></div>
                        Archivos
                        </a>

                        <a class="nav-link collapsed texto rounded-5" href="#" data-bs-toggle="collapse"
                            data-bs-target="#permisos" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-file-shield"></i></div>
                            Permisos
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="permisos" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav rounded-4 me-3">
                                <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>admin/permisos_pendientes">
                                <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-bell"></i></div>
                                Recepción solicitud
                                </a>
                                <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>permisos/aceptados">
                                <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-check"></i></div>
                                Permisos aprobados
                                </a>
                                <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>permisos/rechazados">
                                <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-xmark"></i></div>
                                Permisos rechazados
                                </a>

                            </nav>
                        </div>

                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>admin/permisos">
                        <div class="sb-nav-link-icon"><i class="fa-lg fa-regular fa-registered"></i></div>
                        Registrar permisos
                        </a>

                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>admin/permisos_registrados">
                        <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-check-double"></i></div>
                        Registrados
                        </a>


                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>admin/solicitud">
                        <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-person-chalkboard"></i></div>
                        Datos funcionario
                        </a>
<!-- 
                        <a class="dropdown-item bg-danger text-white rounded-5" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-white"></i>
                            Cerrar Sesión
                        </a> -->
                        <?php }

                        if($rol == 'Funcionario') { ?>
                        <!-- Funcionarios -->
                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>funcionario/solicitudUser">
                            <div class="sb-nav-link-icon"><i class="fa-lg fas fa-hand-paper"></i></div>
                            Solicitar un permiso
                        </a>

                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>funcionario/subirArchivos">
                            <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-file-lines"></i></div>
                            Subir archivos
                        </a>

                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>funcionario/archivosSubidos">
                            <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-file-arrow-up"></i></div>
                            Archivos subidos
                        </a>

                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>funcionario/aprobados">
                            <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-calendar-check"></i></div>
                            Permisos aprobados
                        </a>

                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>funcionario/rechazados">
                            <div class="sb-nav-link-icon"><i class="fa-lg fa-regular fa-calendar-xmark"></i></div>
                            Permisos rechazados
                        </a>

                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>funcionario/archivosRegistrados">
                            <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-calendar-week"></i></div>
                            Permisos registrados
                        </a>

                        <?php }

                        if($rol == 'jefe') { ?>
                        <!-- Jefe -->
                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>jefe/permisosSolicitados">
                            <div class="sb-nav-link-icon"><i class="fa-lg fas fa-inbox"></i></div>
                            Solicitudes
                        </a>

                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>jefe/archivosSubidos">
                            <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-folder"></i></div>
                            Archivos subidos
                        </a>

                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>jefe/archivosUsuarios">
                            <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-id-card-clip"></i></div>
                            Archivos usuarios
                        </a>

                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>jefe/permisosAprobados">
                            <div class="sb-nav-link-icon"><i class="fa-lg fa-regular fa-circle-check"></i></div>
                            Permisos aprobados
                        </a>
                        <a class="nav-link texto rounded-4" href="<?php echo RUTA_ABSOLUTA; ?>jefe/permisosRechazados">
                            <div class="sb-nav-link-icon"><i class="fa-lg fa-regular fa-circle-xmark"></i></div>
                            Permisos rechazados
                        </a>
                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>jefe/trabajoFuncionarios">
                            <div class="sb-nav-link-icon"><i class="fa-lg fas fa-umbrella-beach"></i></div>
                            Trabajo funcionarios
                        </a>
                        <?php
                        }
                        if ($rol == 'Talento_Humano') {
                        ?>
                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>talentoHumano/registrarFuncionario">
                            <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-user-plus"></i></div>
                            Registrar funcionarios
                        </a>
                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>talentoHumano/permisosAprobados">
                            <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-envelope-open-text"></i></div>
                            Permisos aprobados
                        </a>
                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>talentoHumano/permisosRegistrados">
                            <div class="sb-nav-link-icon"><i class="fa-lg fas fa-check-circle"></i></div>
                            Permisos registrados
                        </a>

                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>talentoHumano/archivosSubidos">
                            <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-folder-open"></i></div>
                            Archivos subidos
                        </a>
                        <a class="nav-link texto rounded-5" href="<?php echo RUTA_ABSOLUTA; ?>talentoHumano/archivosRegistrados">
                            <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-folder-closed"></i></div>
                            Archivos registrados
                        </a>

                        <?php
                        }?>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <!-- <div class="small">Logged in as:</div> -->
                    <a class="dropdown-item bg-danger text-white rounded-5" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-white"></i>
                        Cerrar Sesión
                    </a>
                </div>
            </nav>
        </div>
        <!-- End of Sidebar -->
        <!-- End of Topbar -->
        <!-- Content Wrapper -->
        <div id="layoutSidenav_content">
            <main>