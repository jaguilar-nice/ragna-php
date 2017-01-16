<?php
	namespace Rag;
	
	/**
	 * El operador se encarga de la gestión de las peticiones, para derivarlas a los controladores pertinentes.
	 *
	 * Tener más de una petición en la misma instancia del navegador puede ayudar a ralizar redirecciones o tareas Ajax.
	 * Se carga como vista la indicada en el resultado del último controlador, siempre que no se especifique lo contrario.
	 * El operador se peude acceder desde cualquier vista o controlador simplemente haciendo $this.
	 * @package ragnajag
	 */
	class Operador
	{
		/**
		 * Contiene el nombre del controlador actual.
		 */
		public $controlador;
		
		/**
		 * Contiene el nombre de la acción actual.
		 */
		public $accion;
		
		/**
		 * Contiene el nombre de la vista actual.
		 */
		public $vista;
		
		/**
		 * Contiene el nombre de la plantilla actual.
		 */
		public $plantilla = 'defecto.php';
		
		/**
		 * Contiene el nombre de la seccion actual.
		 */
		public $seccion;
		
		/**
		 * Hash que contiene los argumentos variados enviados en la petición.
		 */
		public $args;
		
		/**
		 * Si no es null, indica qué vista debe mostrarse como error. Si esta variable existe, se sobreescribe cualquier otra vista que debiera mostrarse.
		 */
		public $vistaError = null;
		/**
		 * Contiene el nombre del archivo que ha fallado al cargarse.
		 */
		public $vistaErrorArchivo = null;
		
		/**
		 * Variable que almacena la petición que se está ejecutando actualmente.
		 * Sigue un formato parecido a /controlador/acción 
		 */
		public $peticion = false;
					
		/**
		 * Función principal que inicia la navegación.
		 *
		 * El método leerá la variable $_GET['a']. Esperando que esta contenga [nombre_controlador]/[nombre_accion].
		 * En caso de que no se especifique [nombre_accion], esta será "index".
		 * En caso de que no se especifique [nombre_controlador], este será "inicio".
		 *
		 * Además, cargará los generadores especificados si existen: defecto.php y [nombre_controlador].php. Comprobando primero la carpeta /generico/generadores y después /[nombre_controlador]/generadores/
		 */
	 	public function navegar()
		{
			$this->peticion = $_GET['a'];
			
			do
			{
				$this->interpretarPeticion();
				
				$rutaControlador = $this->rutaControlador($this->controlador . ".php");
				if ($rutaControlador) {	require $rutaControlador; }
			} 
			while ($this->peticion);
			
			$this->cargarGeneradores();
				
			if($this->plantilla != null)
			{
				require_once $this->rutaPlantilla($this->plantilla);
			}
			else
			{
				$_ruta_vista = $this->rutaContenido();
				if (file_exists($_ruta_vista))
				{
					require_once $_ruta_vista;
				}
				else
				{
					user_error("No se ha encontrado la vista '" . $_ruta_vista . "'.");
				}
			}
		}
		
		/**
		 * @private
		 * Interpreta la petición almacenada en $this->petición, carga sus valores a la memoria y después borra su contenido.
		 */
		public function interpretarPeticion()
		{
			// Interpreta la petición.
			$valores = explode('/', $this->peticion);
			
			$this->controlador = ($valores[0]) ? $valores[0] : "inicio";
			$this->accion = ($valores[1]) ? $valores[1] : "index";
			
			$this->vista = $this->accion;
			
			//Cargar sección
			$arr = explode('/', $_SERVER['SCRIPT_NAME']);
			$this->seccion = $arr[count($arr) - 2];
			
			$this->peticion = false;
		}
				
		/**
		 * @private
		 * Comprueba las rutas enviadas en $rutas por orden y devuelve la primera que exista como archivo.
		 * @param array $rutas
		 * @return string Primera ruta existente
		 */
		private function selectRuta($rutas)
		{
			foreach ($rutas as $ruta)
			{
				if (file_exists($ruta))
				{
					return $ruta;
				}
			}
			
			return null;
		}
		
		/**
		 * Busca la plantilla indicada y devuelve la ruta correcta. 
		 *
		 * Busca primero en /[seccion]/vistas/plantillas/ y luego en /generico/vistas/plantillas/.
		 * @param string $plantilla Nombre del archivo de la plantilla. Ej: menu.php
		 * @return string Ruta.
		 */
		public function rutaPlantilla($plantilla)
		{
			$rutas[] = 'vistas/plantillas/' . $plantilla;
			$rutas[] = '../generico/vistas/plantillas/' . $plantilla;
			
			if ($ruta = $this->selectRuta($rutas))
			{
				return $ruta;
			}
			else
			{
				$this->vistaError = 'noplantilla.php';
				$this->vistaErrorArchivo = $plantilla;
				return null;
			}
		}
		
		/**
		 * Busca la vista indicada y devuelve la ruta correcta. 
		 *
		 * Busca primero en /[seccion]/vistas/[controlador]/ y luego en /generico/vistas/[controlador]/.
		 * @param string $vista Nombre del archivo de la vista. Ej: mostrar.php
		 * @return string Ruta.
		 */
		function rutaVista($vista)
		{
			$rutas[] = 'vistas/' . $this->controlador . '/' . $vista . ".php";
			$rutas[] = '../generico/vistas/' . $this->controlador . '/' . $vista . ".php";
			
			if ($ruta = $this->selectRuta($rutas))
			{
				return $ruta;
			}
			else
			{
				$this->vistaError = 'novista.php';
				$this->vistaErrorArchivo = $vista;
				return null;
			}
		}
		
		/**
		 * Busca el controlador indicado y devuelve la ruta correcta. 
		 *
		 * Busca primero en /[seccion]/controladores/ y luego en /generico/controladores/.
		 * @param string $controlador Nombre del archivo de la controlador. Ej: usuarios.php
		 * @return string Ruta.
		 */
		function rutaControlador($controlador)
		{
			$rutas[] = 'controladores/' . $controlador;
			$rutas[] = '../generico/controladores/' . $controlador;
			
			if ($ruta = $this->selectRuta($rutas))
			{
				return $ruta;
			}
			else
			{
				$this->vistaError = 'nocontrolador.php';
				$this->vistaErrorArchivo = $controlador;
				return null;
			}
		}
		
		/**
		 * Busca el generador indicado y devuelve la ruta correcta. 
		 *
		 * Busca primero en /[seccion]/generadores/ y luego en /generico/generadores/.
		 * @param string $generador Nombre del archivo de la generador. Ej: usuarios.php
		 * @return string Ruta.
		 */
		function rutaGenerador($generador)
		{
			$rutas[] = 'generadores/' . $generador;
			$rutas[] = '../generico/generadores/' . $generador;
			
			if ($ruta = $this->selectRuta($rutas))
			{
				return $ruta;
			}
			else
			{
				return null;
			}
		}
		
		/**
		 * Busca la vista de error especificada y la coloca como prioritaria.
		 *
		 * Busca primero en /[seccion]/vistas/errores/ y luego en /generico/vistas/errores/.
		 * @param string $vista Nombre del archivo de la controlador. Ej: usuarios.php
		 * @return string Ruta.
		 */
		function rutaError($vista)
		{
			$rutas[] = 'vistas/errores/' . $vista;
			$rutas[] = '../generico/vistas/errores/' . $vista;
			
			if ($ruta = $this->selectRuta($rutas))
			{
				return $ruta;
			}
			else
			{
				user_error("Se ha intentado mostrar un error, pero ni siquiera se ha encontrado la platilla '" . $vista . "' para hacerlo.");
			}
		}
				
		/**
		 * Esta función devuelve la ruta al contenido apropiado elegido por navegar(), así como a cualquier vista de error que deba mostrarse en su lugar.
		 */
		public function rutaContenido()
		{
			$ruta = $this->rutaVista($this->vista);
			
			if ($this->vistaError)
			{
				return $this->rutaError($this->vistaError);
			}
			else
			{
				return $ruta;
			}
		}
		
		/**
		 * @private
		 * Esta función carga los generadores pertinentes para la petición actual.
		 *
		 * Busca 'defecto.php' y luego [controlador].php en /[seccion]/generadores/ y después en /generico/generadores/
		 */
		private function cargarGeneradores()
		{			
			$ruta = $this->rutaGenerador("defecto.php");
			if ($ruta != null) { require_once $ruta; }
			
			$ruta = $this->rutaGenerador($this->controlador . ".php");
			if ($ruta != null) { require_once $ruta; }
		}
		
		/**
		 * Redirecciona hacia el controlador y acción indicados.
		 *
		 * Busca primero en /generico/vistas/[controlador] y luego en /[seccion]/vistas/plantillas.
		 * @param string $plantilla Nombre de la plantilla. Sin la extensión.
		 * @return string Ruta.
		 */
		public function redireccionar($a)
		{
			$this->peticion = $a;
		}
	}
?>