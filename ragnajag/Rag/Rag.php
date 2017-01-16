<?php
	
	/**
	 * Clase que actúa como librería estática para el resto de funcionalidades.
	 * @package ragnajag
	 */
	class Rag
	{
		/**
		 * Objeto encargado de la gestión de archivos de configuración e información. 
		 * @static
		 */
		public static $config;
		
		/**
		 * Objeto encargado de la gestión de sesiones. Así como la gestión de usuarios logeados.
		 * @static
		 */
		public static $sesiones;
		
		/**
		 * Objeto encargado de la gestión de mensajes rápidos para la notificación al usuario de errores y notificaciones.
		 * @static
		 */
		public static $flash;
		
		/**
		 * Objeto encargado de gestionar la lógica de navegación por las páginas y actúa de enlace entre controladores, vistas y plantillas.
		 * @static
		 */
		public static $operador;
		
		/**
		 * Referencia al usuario logueado actualmente.
		 * @static
		 */
		public static $us;
		
		/**
		 * Función estática encargada de la inicialización de los objetos de la clase.
		 * @param string $nombre Indica el nombre del archivo (sin la extensión) donde se almacena la configuración del servidor. Por defecto "server".
		 * @static 
		 */
		public static function cargar()
		{
			require_once '../ragnajag/Rag/Hash.php';
			require_once '../ragnajag/Rag/Config.php';
			require_once '../ragnajag/Rag/Flash.php';
			require_once '../ragnajag/Rag/Mensaje.php';
			require_once '../ragnajag/Rag/Sesiones.php';
			require_once '../ragnajag/Rag/Operador.php';
		
			//Cargamos el gestor de configuraciones
			self::$config = new Rag\Config();
			
			//Cargamos el gestor de Sesiones
			self::$sesiones = new Rag\Sesiones();
			
			//Cargamos el gestor de mensajes
			self::$flash = new Rag\Flash();
			
			//Cargamos el sistema de navegacion
			self::$operador = new Rag\Operador();
		}
	}
?>