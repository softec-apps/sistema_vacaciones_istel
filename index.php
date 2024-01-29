<?php
include_once("redirection.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login</title>

    <!-- Jquery cdn -->
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Script de flash_messages -->
    <script src="js/flash_messages.js"></script>
    <!--CDN Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Custom fonts for this template-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <!-- Custom styles for this template-->
    <link href="<?php echo $ruta_absoluta; ?>css/sb-admin-2.min.css" rel="stylesheet">

    <!--Css personalizados -->
    <link href="<?php echo $ruta_absoluta; ?>css/personal/estilos_personales.css" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo $ruta_absoluta; ?>css/personal/styles.css">
    <!-- CDN Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body class="fucion">

    <div class="container mx-auto">

<?php
include_once "flash_messages.php";
$message = '';
$type = '';
$flash_message = display_flash_message();
if (isset($flash_message)) {
    $message = $flash_message['message'];
    $type = $flash_message['type'];
}

?>
        <div class="row justify-content-center mx-auto">
            <div class="col-xl-6 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5 rounded-5" id="inicio_sesion">
                    <div class="card-body p-1">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-12">
                                <div class=" px-4 py-5 pb-4">
                                    <div class="text-center">
                                    <img src="./imagenes_defecto/logo_instituto.png" class="img-fluid w-25 mb-2">
                                        <h1 class="h4 text-gray-800 mb-4">Gestion de permisos y vaciones</h1>
                                    </div>
                                    <hr class="bg-dark m-4">
                                    <form class="user m-4" action="login" method="post">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="usuario" name="usuario" aria-describedby="emailHelp"
                                                placeholder="Introducir el Usuario...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="clave" name="clave" placeholder="Contresaña">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Mostrar Contraseña</label>
                                            </div>
                                        </div>
                                        <hr class="bg-dark mb-4 mt-4">
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Iniciar sesión
                                        </button>
                                        <hr class="bg-dark mb-4 mt-4">
                                        <div class="text-center p-1 pb-0">
                                            <a class="small text-decoration-none" href="ForgotPassword/Recuperar-Contraseña">Olvido Su Contraseña?</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
        const claveInput = document.getElementById("clave");
        const customCheck = document.getElementById("customCheck");

        customCheck.addEventListener("change", function() {
            if (customCheck.checked) {
            // Si el checkbox está marcado, muestra la contraseña
            claveInput.type = "text";
            } else {
            // Si el checkbox no está marcado, oculta la contraseña
            claveInput.type = "password";
            }
        });
        });
        $(document).ready(function() {
            showFlashMessages('<?php echo $message; ?>', '<?php echo $type; ?>');
        });
    </script>


    <!-- Bootstrap core JavaScript-->
    <!-- <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script> -->

    <!-- Core plugin JavaScript-->
    <!-- <script src="vendor/jquery-easing/jquery.easing.min.js"></script> -->

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>