<?php
include_once "redirection.php";
include_once "flash_messages.php";
	session_start();
	session_destroy();
	create_flash_message(
		'Sesion Cerrada',
		'success'
	);

	redirect(RUTA_ABSOLUTA . "inicio");
?>