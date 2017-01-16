<?php
	/**
	 * Archivo-librería de funciones y utilidades.
	 * @package ragnajag
	 */
	namespace Ops;

	/**
	 * Alias de htmlentities($string, ENT_QUOTES);
	 * @param string $string String a modificar.
	 * @return string String modificada.
	*/
	function quitarEntidades($string)
	{
		return htmlentities($string, ENT_QUOTES);
	}
	
	/**
	 * Alias de html_entity_decode($string, ENT_QUOTES);
	 * @param string $string String a modificar.
	 * @return string String modificada.
	*/
	function ponerEntidades($string)
	{
		return html_entity_decode($string, ENT_QUOTES);
	}
	
	/**
	 * Comprueba que el mail pasado tenga un formato correcto (nombre@dominio.algo).
	 * @param string $mail Mail a comprobar
	 * @return bool True si $mail es un mail válido. False en caso contrario.
	*/
	function esMail($mail)
	{
		return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", $mail);
	}
	
	/**
	 * Esta función va devolviendo true o false de forma alterna cada vez que se la llama. False la primera vez, True la segunda, False la tercera, etc.
	 * @return bool
	*/
	function swing()
	{
		static $fila;
		$fila = ($fila) ? false : true;
	    return $fila;
	}
	
	/**
	 * Descapitaliza (pone en minusculas) la primera letra de una string.
	 * @param string $str String a descapitalizar.
	 * @return string String descapitalizada.
	*/
	function struncapitalize($str)
	{
		$str[0] = strtolower($str[0]);
		return (string)$str;
	}
	
	/**
	 * Devuelve el plural en castellano de la palabra enviada.
	 * @param string $palabra Palabra a pluralizar.
	 * @return string Palabra pluralizada.
	*/
	function pluralizar($palabra)
	{
		if (!$palabra) { return null; }
		if (preg_match('/[aeiougcmAEIOUGCM]$/', $palabra))
		{
			return $palabra . 's';
		}
		else
		{
			$ultima = substr($palabra, -1, 1);
			if ($ultima == 'z')
			{
				return substr($palabra, 0, -1) . 'ces';
				
			}
			elseif($ultima == 'x' || $ultima == 'j')
			{
				return $palabra;
			}
			elseif ($ultima == 'y')
			{
				return substr($palabra, 0, -1) . "is";
			}
			else
			{
				return $palabra . 'es';
			}
		}
	}
?>