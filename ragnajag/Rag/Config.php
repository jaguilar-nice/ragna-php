<?php
	namespace Rag;
	
	/**
	 * Clase encargado de la gestión de archivos de configuración e información.
	 * Además es el encargado de realizar los ajustes de configuración en tiempo de ejecución.
	 *
	 * La clase lee de forma dinámica los archivos XML en la carpeta config/ y permite leer sus resultados mediante el método mágico __get(). 
	 * Para acceder a un archivo, se debe referenciar el nombre del archivo, seguido del nodo a leer. 
	 * Ejemplo: $config->archivo->nodo.
	 * @package ragnajag
	 */
	class Config
	{
		private $info = array();
		
		/**
		 * Constructor. Carga las configuraciones básicas que indique RAG_ENV.
		 */
		public function __construct()
		{			
			$this->randomize();
			
			$this->setTimeZone();
			
			$this->setErrorLevel();
			
			$this->limpiarImputs();
		}
		
		private function randomize()
		{
			mt_srand(microtime() * 10000);
		}
		
		private function setTimeZone()
		{
			date_default_timezone_set($this->_entorno->timeZone);
		}
		
		private function setErrorLevel()
		{
			switch ($this->_entorno->error)
			{
				case 0:
					error_reporting(0);
					break;
				
				case 1:
					error_reporting(E_ALL ^ E_NOTICE);
					break;
				
				case 2:
					error_reporting(-1);
					break;
			}
		}
		
		private function limpiarImputs()
		{
			$filter = \InputFilter::getInstance();
			$fechas_get = array();
			$fechas_post = array();
			
			//limpiar Get y Post [Filter in Input]
			foreach ($_GET as $k => $v)
			{
				if (strstr($k, "!RagDate") == $k)
				{
					$fechas_get[] = $v;
				}
				else
				{
					$_GET[$k] = $filter->process($v);
				}
			}
			
			foreach ($_POST as $k => $v)
			{
				if (strstr($k, "!RagDate") == $k)
				{
					$fechas_post[] = $v;
				}
				else
				{
					$_POST[$k] = $filter->process($v);
				}
			}
			
			//Ejecutar fechas
			foreach ($fechas_get as $f)
			{
				$this->arreglar_fecha($f, &$_GET);
			}
			
			foreach ($fechas_post as $f)
			{
				$this->arreglar_fecha($f, &$_POST);
			}
		}
		
		private function arreglar_fecha($f, $var)
		{
			$prename = str_replace(array("[", "]"), array("_", ""), $f);
			$array_name = explode("[", $f);
			$var[$array_name{0}][rtrim($array_name[1], "]")] = $var[$prename . '_ano'] . "-" . $var[$prename . '_mes'] . "-" . $var[$prename . '_dia'];
		}
		
		/**
		 * Método mágico que permite obtener la información almacenada en los módulos XML.
		 * 
		 * La ruta es la siguiente: Rag::$config->{nombre archivo}->nombreNodo->nombreNodo.
		 * Ejemplo: Rag::$config->servers->produccion->timeZone
		 *
		 * Adicionalmente, existen 2 palabras clave a las que se permite acceder: _server y _database.
		 * _server carga la el nodo de configuración de servers.xml indicado por la constante RAG_ENV
		 * Ejemplo: Rag::$config->_server->timeZone devolvería lo mismo que Rag::$config->servers->produccion->timeZone si RAR_ENV fuera 'produccion'.
		 * _database carga el nodo de configuración de databases.xml indicado por la variable 'db' indicada en la configuración de _server.
		 * @param string $key Nombre del nodo a acceder.
		 * @return mixed
		 */
		public function __get($key)
		{
			if ($key == "_entorno")
			{
				return $this->entornos->get(RAG_ENV);
			}
			elseif ($key == "_db")
			{
				return $this->databases->get($this->_entorno->db);
			}
			else
			{
				if (!$this->info[$key])
				{
					$this->info[$key] = $this->deserialize('../config/' . $key . '.xml');
				}
				
				return $this->info[$key];
			}
		}
		
		private function deserialize($file)
		{
				if (file_exists($file))
				{				
					$xmlStr = file_get_contents($file);
					$xmlObj = simplexml_load_string($xmlStr);
					return $this->objectsIntoHash($xmlObj);
				}
				else
				{
					user_error("No se ha encontrado el archivo de configuracion '" . $file . "'.");
				}
		}
		
		private function objectsIntoHash($arrObjData, $arrSkipIndices = array())
		{
			$arrData = array();
		   
			// if input is object, convert into array
			if (is_object($arrObjData)) 
			{
				$arrObjData = get_object_vars($arrObjData);
			}
			
			if (is_array($arrObjData)) 
			{
				if (count($arrObjData) > 0)
				{
					foreach ($arrObjData as $index => $value) 
					{
						if (is_object($value) || is_array($value)) 
						{
							$value = $this->objectsIntoHash($value, $arrSkipIndices); // recursive call
						}
						
						if (in_array($index, $arrSkipIndices)) 
						{
							continue;
						}
						$arrData[$index] = $value;
					}
				}
				else
				{
					$arrData = "";
				}
			}
			
			if (!empty($arrData))
			{
				$ret = new Hash();
				$ret->setValores($arrData);
				return $ret;
			}
			else
			{
				return $arrData;
			}
		}
	}
?>