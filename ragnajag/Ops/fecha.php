<?php
	/**
	 * Archivo-librería de funciones y utilidades para tratar fechas.
	 * @package ragnajag
	 */
	 namespace Ops;
	 
	 
	 /**
	 * Devuelve un array con los meses en castellano. "1" => "Enero", "2" => "Febrero"...
	 *
	 * @return array Meses en castellano.
	*/
	function mesesArray()
	{
		return array ("1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre");
	}
	
	/**
	 * Devuelve un array con los días de la semana en castellano. "0" => "Domingo", "1" => "Lunes"...
	 *
	 * @return array Días en castellano.
	*/
	function diasArray()
	{
		return array ("0" => "Domingo", "1" => "Lunes", "2" => "Martes", "3" => "Mi&eacute;rcoles", "4" => "Jueves", "5" => "Viernes", "6" => "S&aacute;bado");
	} 
	
	/**
	 * Imprime la fecha con formato 'jueves, 12 de Agosto de 2007'.
	 *
	 * @param time $time Variable time, si es null, se sustituye por NOW. null por defecto.
	 * @return string Días en castellano.
	*/
	function fecha($time = null)
	{
		if ($time == null) { $time = time(); }
		$meses = mesesArray();
		$dias = diasArray();
		
		return $dias[date('w', $time)] . ", " . date('j', $time) . " de " . $meses[date('n', $time)] . " de " . date('Y', $time);
	}
	
	/**
	 * Convierte fechas en formato español (d-m-Y) a time.
	 *
	 * @param string $string String que contiene una fecha en formato d-m-Y o d/m/Y.
	 * @return time
	*/
	function parseFecha($string)
	{
		if (strpos($string, "-") !== false)
		{

			$arr = explode("-", $string);
		}
		elseif (strpos($string, "/") !== false)
		{

			$arr = explode("/", $string);
		}
		
		if (count($arr) == 3)
		{	
			if ($arr[2] < 100)
			{
				$arr[2] += 2000;
			}
			
			return mktime(0, 0, 0, $arr[1], $arr[0], $arr[2]);
		}
		else
		{

			return 0;
		}
	}
	
	/**
	 * Genera una string para indicar la lejanía de una fecha en días. Ejemplo: "hace 45 día(s)". A partir de los 2 años, la función empezará a contar el tiempo por años.
	 * @param time $fecha Fecha desde la que comparar.
	 * @return string
	*/
	function desde($fecha)
	{
		$inicio = $fecha;
		$fin = time();
		$res = ($fin - $inicio)/60/60/24;
		
		if (floor($res) == 0)
		{
			return "Hoy";
		}
		elseif ($res > 365 * 2)
		{
			return "hace " . floor($res / 365) . " a&ntilde;o(s)";
		}
		else
		{
			return "hace " . floor($res) . " d&iacute;a(s)";
		}
	}
?>