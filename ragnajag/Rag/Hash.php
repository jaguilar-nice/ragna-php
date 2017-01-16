<?php
	namespace Rag;
	/**
	 * Implementa un Hash personalizado con acceso por __set y __get.
	 * Implementa la interface Iterator.
	 * @package ragnajag
	*/
	class Hash implements \Iterator
	{
		private $_valores = array();
		private $_key = 0;
		
		/**
		 * Constructor.
		 * @param array $array Recibe un array que contiene los valores del Hash. Vacía por defecto.
		*/
		public function __construct($array = array())
		{
			$this->setValores($array);
		}
		
		/**
		 * Devuelve la array de valores dentro del hash.
		 * @return array
		*/
		public function getValores()
		{
			return $this->_valores;
		}
		
		/**
		 * Sustituye la array de valores del hash por la indicada.
		 * @param array $array
		*/
		public function setValores($array)
		{
			$this->_valores = $array;
		}
		
		/**
		 * Edita o inserta los valores coincidentes en la array, pero sin alterar los valores que ya estuvieran en el Hash y no deban editarse.
		 * @param array $array
		*/
		public function editarValores($array)
		{
			foreach ($array as $k => $v)
			{
				$this->set($k, $v);
			}
		}
		
		/**
		 * Añade los valores al Hash.
		 * @param array $array
		*/
		public function addValores($array)
		{
			if (is_array($this->_valores))
			{
				$this->_valores = array_merge($this->_valores, $array);
			}
			else
			{
				$this->_valores = $array;
			}
		}
		
		/**
		 * Añade el valor indicado al Hash. Este valor puede ser un único valor o una combinación addValor($key, $value).
		 * @param mixed $args
		*/
		public function addValor()
		{
			$args = func_get_args();
			$num = func_num_args();
			if ($num == 1)
			{
				$this->_valores[] = $args[0];
			}
			else
			{
				$this->_valores[$args{0}] = $args[1];
			}
		}
		
		/**
		 * Devuelve el valor indicado.
		 * @param mixed $nombre Clave del valor a recuperar.
		 * @return mixed
		*/
		public function get($nombre)
		{
			return $this->_valores[$nombre];
		}
		
		/**
		 * Devuelve el valor indicado.
		 * @param mixed $nombre Clave del valor a recuperar.
		 * @return mixed
		*/
		public function __get($nombre)
		{
			return $this->get($nombre);
		}
		
		/**
		 * Asigna el valor asignado.
		 * @param mixed $nombre Clave del valor a asignar.
		 * @param mixed $valor Valor a asignar.
		*/
		public function set($nombre, $valor)
		{
			$this->_valores[$nombre] = $valor;
		}
		
		/**
		 * Asigna el valor indicado.
		 * @param mixed $nombre Clave del valor a asignar.
		 * @param mixed $valor Valor a asignar.
		*/
		public function __set($nombre, $valor)
		{
			$this->set($nombre, $valor);
		}
		
		/**
		 * Comprueba si existe la clave indicada.
		 * @param mixed $nombre Clave del valor a comprobar.
		 * @param bool $True si existe.
		*/
		public function __isset($nombre)
		{
			return isset($this->_valores[$nombre]);
		}
	
		/**
		 * Destruye la entrada indicada.
		 * @param mixed $nombre Clave del valor a destruir.
		*/
		public function __unset($nombre)
		{
			unset($this->_valores[$nombre]);
		}
		
		/**
		 * Implementa Iterator. Devuelve el elemento actual.
		 * @return mixed
		*/
		public function current()
		{
			$key = $this->_key;
			if (isset($key))
			{
				$keys = $this->keys();
				return $this->_valores[$keys{$key}];
			}
			else
			{
				return null;
			}
		}
		
		/**
		 * Implementa Iterator. Devuelve la clave del elemento actual.
		 * @return mixed
		*/
	 	public function key()
		{
			$keys = $this->keys();
			return $keys[$this->_key];
		}
		
		/**
		 * Devuelve las claves de todos los elementos.
		 * @return array
		*/
		private function keys()
		{
			return array_keys($this->_valores);
		}
		
		/**
		 * Implementa Iterator. Avanza al siguiente elemento.
		*/
	 	public function next()
		{
			$this->_key++;
		}
		
		/**
		 * Implementa Iterator. Rebobine la Iterator al primer elemento.
		*/
	 	public function rewind()
		{
			$this->_key = 0;
		}
		
		/**
		 * Implementa Iterator. Comprueba si la posición actual es válido.
		 * @return bool
		*/
	 	public function valid()
		{
			$keys = $this->keys();
			$key = $keys[$this->_key];
			return isset($this->_valores[$key]);
		}
		
		/**
		 * Devuelve la cantidad de entradas que posee el Hash.
		 * @return int Cantidad de entradas.
		*/
		public function count()
		{
			return count($this->_valores);
		}
		
		/**
		 * Devuelve el contenido del Hash para ser debugeado.
		 * @return string
		*/
		public function __toString()
		{
			$tab = "";
			$level = (func_num_args() > 0) ? func_get_arg(0) : 0;
			
			for($i = 0; $i < $level; $i++)
			{
				$tab .= "&nbsp;&nbsp;";
			}
			
			$str = "";
			foreach ($this->_valores as $key => $val)
			{
				$s .= $tab . "<font color='purple'>" . $key . "</font> <b>-></b> ";
				if (get_class($val) == "RagnaHash")
				{
					$val->__toString($level + 1);
				}
				else
				{
					$s .= "<font color='darkcyan'>" . $val . "</font>";
				}
				$s .= "<br/>";
			}
			
			return $s;
		}
		
		/**
		 * Invierte el orden de los elementos del Hash.
		*/
		public function revertir()
		{
			$this->_valores = array_reverse($this->_valores);
		}
	}
?>