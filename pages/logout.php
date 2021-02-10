<?php
	session_start();
	// Zerstört die Session und leitet auf index.php weiter
	session_destroy();

	header("Location: ../index.php");
?>
