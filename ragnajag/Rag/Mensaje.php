<?php
	namespace Rag;
	
	/**
	 * Clase asociada a RagFlash para enviar mensajes al usuario.
	 * @package ragnajag
	*/
	class Mensaje
	{
		
		const NEUTRAL = 0;
		const NOTIFICACION = 1;
		const ERROR = 2;
		const ADVERTENCIA = 3;
		const DENEGADO = 4;
		
		/**
		 * Guarda el contenido del mensaje.
		*/
		public $texto;

		/**
		 * Guarda el tipo del mensaje.
		*/
		public $tipo = self::NEUTRAL;
		
		
		/**
		 * Constructor. Crea un mensaje de un tipo concreto.
		 * @param string $texto Texto del mensaje.
		 * @param int $tipo Tipo del mensaje. 0 por defecto.
		*/
		public function __construct($texto, $tipo = self::NEUTRAL)
		{
			$this->texto = $texto;
			$this->tipo = $tipo;
		}
	}
?>