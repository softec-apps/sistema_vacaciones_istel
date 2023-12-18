<?php
include_once "redirection.php";
	session_start();
	session_destroy();

	redirect("inicio");
?>