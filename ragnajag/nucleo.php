<?php
	/**
	 * Núcleo de Ragnajag.
	 * @package ragnajag
	 */
	 define('RAG_VER', '0.7');
	
	/**
	 * Función que carga las clases automáticamente, buscándolas en las carpetas pertinentes.
	 */
	function autoLoad($nombre_clase)
	{
		// Modelos de la sección actual
		if (file_exists('modelos/' . $nombre_clase . '.php'))
		{
			require_once 'modelos/' . $nombre_clase . '.php';
		}
		// Modelos Genéricos
		elseif (file_exists('../generico/modelos/' . $nombre_clase . '.php'))
		{
			require_once '../generico/modelos/' . $nombre_clase . '.php';
		}
		// 3rd Party incluidos en Ragnajag
		elseif (file_exists('../ragnajag/vendor/otros/' . $nombre_clase . '.php'))
		{
			require_once '../ragnajag/vendor/otros/' . $nombre_clase . '.php';
		}
		// 3rd Party incluidos en el proyecto
		elseif (file_exists('../lib/' . $nombre_clase . '.php'))
		{
			require_once '../lib/' . $nombre_clase . '.php';
		}
	}
	
	spl_autoload_register('autoLoad');
	
	
	//Cargar operaciones y funciones de Ragnajag
	require_once '../ragnajag/Ops/html.php';
	require_once '../ragnajag/Ops/util.php';
	require_once '../ragnajag/Ops/fecha.php';
	
	require_once '../ragnajag/Rag/Rag.php';
	
	require_once '../ragnajag/vendor/ActiveRecord/ActiveRecord.php';
	
	//Arrancar motor Rag.
	Rag::cargar();
	
	//Conectar DB
	ActiveRecord\Config::initialize(function($cfg)
	{
		$cfg->set_model_directory('modelos/');
		$cfg->set_connections(Rag::$config->databases->getValores());
	});
?>