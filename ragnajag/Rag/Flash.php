<?php	
	namespace Rag;

	/**
	 * Clase encargada de la gestión de mensajes rápidos para la notificación al usuario de errores y notificaciones.
	 * @package ragnajag
	*/
	class Flash
	{	
		/**
		 * Array contenedora de mensajes, listos para ser mostrados por pantalla.
		*/
		public $mensajes = array();
		
		/**
		 * Constructor. Carga los mensajes guardados en sesión.
		*/
		public function __construct()
		{
			if (is_array(\Rag::$sesiones->flash))
			{
				$this->mensajes = \Rag::$sesiones->flash;
			}
			\Rag::$sesiones->flash = null;
		}
		
		/**
		 * Guarda un mensaje.
		 * @param string $msj Mensaje a guardar.
		 * @param int $tipo Tipo de mensaje. Coincide con los tipos de RagMensaje.
		*/
		public function msg($msj, $tipo)
		{
			\Rag::$sesiones->flash[] = new Mensaje($msj, $tipo);
			$this->mensajes[] = new Mensaje($msj, $tipo);
		}
		
		/**
		 * Guarda un mensaje de tipo RagMensaje::ERROR
		 * @param string $msj Mensaje a guardar.
		*/
		public function error($msj)
		{
			$this->msg($msj, Mensaje::ERROR);
		}
		
		/**
		 * Guarda un mensaje de tipo RagMensaje::ADVERTENCIA
		 * @param string $msj Mensaje a guardar.
		*/
		public function advertir($msj)
		{
			$this->msg($msj, Mensaje::ADVERTENCIA);
		}
		
		/**
		 * Guarda un mensaje de tipo RagMensaje::NOTIFICACION
		 * @param string $msj Mensaje a guardar.
		*/
		public function notificar($msj)
		{
			$this->msg($msj, Mensaje::NOTIFICACION);
		}
		
		/**
		 * Guarda un mensaje de tipo RagMensaje::NEUTRAL
		 * @param string $msj Mensaje a guardar.
		*/
		public function neutral($msj)
		{
			$this->msg($msj, Mensaje::NEUTRAL);
		}
		
		/**
		 * Guarda un mensaje de tipo RagMensaje::DENEGADO
		 * @param string $msj Mensaje a guardar.
		*/
		public function denegar($msj)
		{
			$this->msg($msj, Mensaje::DENEGADO);
		}
		
		/**
		 * Genera el código HTML necesario para mostrar los mensajes almacenados.
		 * @return string Código HTML.
		*/
		public function dump()
		{
			$ret = "";
			if (count($this->mensajes) > 0)
			{
				$ret .= '<ul class="errores">';
				
				foreach ($this->mensajes as $mensaje)
				{
					$ret .= '<li class="error' . $mensaje->tipo . '">' . $mensaje->texto .'</li>';
				}
				
				$ret .= '</ul>';
			}
			
			return $ret;
		}
	}
?>