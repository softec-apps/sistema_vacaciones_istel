<?php
include_once "redirection.php";
include_once "flash_messages.php";
	session_start();
	session_destroy();

	redirect(RUTA_ABSOLUTA . "inicio");
?>