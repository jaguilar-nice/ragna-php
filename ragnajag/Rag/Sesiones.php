<?php
	namespace Rag;

	/**
	 * Clase encargada de la gestión de sesiones. Mediante los métodos mágicos __get y __set.
	 * @package ragnajag
	 */
	class Sesiones
	{
		/**
		 * Constructor. Inicializa las sesiones. Llama a session_start().
		 * Además, busca y carga en memoria (Rag::$us) los datos del usuario logueado. Buscando primero en las sesiones y luego en las cookies.
		 */
		public function __construct()
		{			
			session_start();
			
			// Carga el usuario en memoria
			$this->logIn($this->cargarUsuarioLogeado());
		}
		
		/**
		 * Asigna el valor indicado.
		 * @param mixed $nombre Clave del valor a asignar.
		 * @param mixed $valor Valor a asignar.
		 */
		public function __set($key, $valor)
		{
			$_SESSION[$this->getPrefijo() . $key] = $valor;
		}
		
		/**
		 * Devuelve el valor indicado.
		 * @param mixed $nombre Clave del valor a recuperar.
		 * @return mixed
		 */
		public function __get($key)
		{
			return $_SESSION[$this->getPrefijo() . $key];
		}
		
		/**
		 * Destruye la sesión indicada.
		 * @param mixed $nombre Clave del valor a destruir.
		 */
		public function __unset($nombre)
		{
			$_SESSION[$this->getPrefijo() . $nombre] = null;	
			session_unregister($this->getPrefijo() . $nombre);
		}
		
		/**
		 * Destruye todas las sesiones.
		 */
		public function destruirSesiones()
		{
			session_unset();
			session_destroy();
		}
		
		/**
		 * Devuelve el prefijo de las sesiones y las cookies, configurado en config/entornos.xml.
		 * @returns string
		 */
		public function getPrefijo()
		{
			// En caso de no existir, genera un prefijo para las sesiones.
			if (!isset(\Rag::$config->_entorno->prefix))
			{
				\Rag::$config->_entorno->prefix = 'rag_' . substr(RAG_ENV, 0, 3) . str_replace(".", "_", $_entorno['SERVER_NAME']) . '_';
			}
			
			return \Rag::$config->_entorno->prefix;
		}
		
		/**
		 * Logea al usuario.
		 * @param IUsuario $us Usuario a logear.
		 * @param bool $recordar Si es true, guarda una cookie del usuario. false por defecto.
		 */
		public function logIn($us, $recordar = false)
		{		
			if($us)
			{
				if($recordar)
				{
					\Rag::$this->recordar($us);
				}
				
				$this->usuario_login = $us->getLogin();
				$this->usuario_password = $us->getPassword();
				\Rag::$us = $us;
			}
			else
			{
				$this->usuario_login = null;
				\Rag::$us = null;
			}
		}
		
		/**
		 * Deslogea al usuario logeado y borra la cookie.
		 */
		public function logOut()
		{
			$this->olvidar();
			$this->usuario_login = null;
			\Rag::$us = null;
		}
		
		/**
		 * Guarda una cookie del usuario logueado.
		 * @param IUsuario $us Usuario a recordar.
		 */
		public function recordar($us)
		{
			setcookie($this->getPrefijo() . "_login", $us->getLogin(), time() + 3600 * 24 * 30);
			setcookie($this->getPrefijo() . "_password", $us->getPassword(), time() + 3600 * 24 * 30);
		}
		
		/**
		 * Borra la cookie del usuario logueado.
		 */
		public function olvidar()
		{
			setcookie($this->getPrefijo() . "_login", null, time() - 1);
			setcookie($this->getPrefijo() . "_password", null, time() - 1);
		}
		
		private function cargarUsuarioLogeado()
		{
			$registro = \Rag::$config->_entorno->user;
			
			if (!empty($registro))
			{
				$tabla = \Ops\pluralizar($registro);
				
				//Si el usuario está en sesión, cargar sus datos.
				if ($this->usuario_login != null)
				{
					return Dag::$t->{$tabla}->encontrarUnoConLogin(
							$this->usuario_login, 
							$this->usuario_password
						);
				} // Si no, buscarlo en las cookies.
				else if($_COOKIE[$this->getPrefijo() . "login"] != null)
				{
					InputFilter::getInstance()->process($this->getPrefijo() . "login");
					InputFilter::getInstance()->process($this->getPrefijo() . "password");

					return Dag::$t->{$tabla}->encontrarUnoConLogin(
							$_COOKIE[$this->getPrefijo() . "login"],
							$_COOKIE[$this->getPrefijo() . "password"]
						);
				}
				else
				{
					return null;
				}
			}
			else
			{
				// Si no está habilitada la gestión de usuarios, devuelve null;
				return null;
			}
		}
	}
?>