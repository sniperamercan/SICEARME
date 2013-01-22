<?php

	session_start();
	
	require_once '../includes/constantes.inc.php';
	
	session_destroy();	
	
	session_write_close();

	header("location: ".PERM_ERROR_ACCESS);
	
	die();
?>