<?php
		// Especificamos el entorno en el que trabajaremos. 
		// Debe coincidir con una configuracion de config/entornos.xml
		define("RAG_ENV", "desarrollo");
		
		// Cargamos las funciones básicas de Ragnajag
		require_once '../ragnajag/nucleo.php';
		
		// Iniciamos la navegación.
		Rag::$operador->navegar();
?>