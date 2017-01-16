<?php
	/**
	 * Archivo-librería de funciones y utilidades para generar código HTML.
	 * @package ragnajag
	 */
	 namespace Ops;
	 
	 
	/**
	 * Imprime la variable especificada en pantalla. Envuelta en tags &lt;pre&gt;.
	 * @param mixed $var Variable a ser mostrada.
	*/
	function debug($var)
	{
		echo "\n\n<pre>\n";
		var_dump($var);
		echo "</pre>\n<br/>\n\n";
	}
	
	/**
	 * Genera un tag <link> que apunta a una pagina de estilo (css) localizada en /estatico/css/[nombre_archivo].
	 * @param string $name Nombre del archivo o ruta relativa. Ejemplo: "estilo.css" o "web/web.css".
	 * @param string $media Establece la propiedad 'media' del tag. Por defecto es 'screen'.
	 * @return string Tag HTML.
	*/
	function stylesheet($name, $media = 'screen')
	{
		return '<link rel="stylesheet" type="text/css" href="../estatico/css/' . $name . '" media="' . $media . '" />' . "\n";
	}
	
	/**
	 * Genera un tag <link> que apunta a un icono (.ico) localizado en /estatico/icon/[nombre_archivo].
	 * @param string $name Nombre del archivo o ruta relativa. Ejemplo: "fav.ico" o "web/web.ico".
	 * @return string Tag HTML.
	*/
	function favicon($name)
	{
		return '<link rel="shortcut icon" href="../estatico/icon/' . $name . '" />' . "\n";
	}
	
	/**
	 * Genera un tag <script> que apunta a un script javascript (.js) localizado en /estatico/js/[nombre_archivo].
	 * @param string $name Nombre del archivo o ruta relativa. Ejemplo: "script.js" o "web/web.js".
	 * @return string Tag HTML.
	*/
	function javascript($name)
	{
		return '<script src="../estatico/js/' . $name . '" type="text/javascript"></script>' . "\n";
	}
	
	/**
	 * Prepara una string para ser imprimida en HTML.
	 *
	 * Convierte los saltos de línea en <br/> y le aplica la función BBCode.
	 * @param string $texto Texto a textualizar.
	 * @return string Texto textualizado.
	*/
	function textualizar($texto)
	{
      //$texto = utf8_encode($texto);
      $texto = nl2br($texto); 
      $texto = BBCode($texto);
	  
      return $texto;
	}
	
	/**
	 * Convierte el BBCode de la string en código HTML.
	 *
	 * Convierte:
	 * [i] -> &lt;i&gt;
	 * [b] -> &lt;b&gt;
	 * [u] -> &lt;u&gt;
	 * [img] -> &lt;img&gt;
	 * [center] -> &lt;center&gt;
	 * @param string $texto Texto a convertir.
	 * @return string Texto convertido.
	*/
	function BBcode($texto)
	{
	   $a = array(
		  "/\[i\](.*?)\[\/i\]/is",
		  "/\[b\](.*?)\[\/b\]/is",
		  "/\[u\](.*?)\[\/u\]/is",
		  "/\[img\](.*?)\[\/img\]/is",
		  "/\[center\](.*?)\[\/center\]/is"
	   );
	   
	   $b = array(
		  "<i>$1</i>",
		  "<b>$1</b>",
		  "<u>$1</u>",
		  "<img src='$1'/>",
		  "<center>$1</center>"
	   );
	   $texto = preg_replace($a, $b, $texto);
	   return $texto;
	} 
	
	/**
	 * Devuelve el código de un tag HTML.
	 * @param string $tag Tipo de tag a crear. Por ejemplo "img" para un tag <img>.
	 * @param array $otros Array que contiene las propiedades del elemento. Vacía por defecto.
	 * @param string $valor Si no es null, contenido dentro de tag de abertura y cierre  (<b>Contenido</b>); Si es null, el tag será autoconclusivo (<br />). null por defecto.
	 * @return string Tag HTML.
	*/
	function tag($tag, $otros = array(), $valor = null)
	{
		if ($otros['ttt'])
		{
			$otros['onmouseover'] = "javascript:ttt('" . $otros['ttt'] . "');";
			$otros['onmouseout'] = "httt();";
			unset($otros['ttt']);
		}
		
		if ($otros['floatie'])
		{
			$otros['onmouseover'] = "showfloatie('" . $otros['floatie'] . "', event);";
			$otros['onmouseout'] = "hidefloatie();";
			unset($otros['floatie']);
		}
		
		if ($otros['ajaxFloatie'])
		{
			$otros['onmouseover'] = "ajaxfloatie('index.php', '" . $otros['ajaxFloatie'] . "', event);";
			$otros['onmouseout'] = "hidefloatie();";
			unset($otros['ajaxFloatie']);
		}
		
		$r  = "<$tag ";
		foreach ($otros as $k => $v)
		{
			$r .= "$k=\"$v\" ";
		}
		
		if($valor == null) 
		{
			$r .= "/>";	
		}
		else
		{
			$r .= ">" . $valor . "</" . $tag . ">";
		}
		
		return $r;
	}
	
	/**
	 * Genera un tag tipo <img>.
	 * @param string $src Src de la imagen. Si el nombre contiene 'http://', esta se buscará de forma absoluta; en caso contrario, se buscará en /estatico/img/
	 * @param array $otros Array que contiene las propiedades del elemento. Vacía por defecto.
	 * @return string Tag HTML.
	*/
	function imagen($src, $otros = array())
	{
		if (strstr($src, "http://") == $src)
		{
			$otros['src'] = $src;
		}
		else
		{
			$otros['src'] = "../estatico/img/" . $src;
		}
		$otros['alt'] = $src;
		return tag("img", $otros);
	}
	
	/**
	 * Genera un link Ajax.
	 * @param string $valor Texto del link.
	 * @param string $layer_id Id del Layer(div) donde se cargará el resultado de ajax.
	 * @param string $accion Contenido get de la llamada. Ej: (a=usuarios/mostrar&id=1).
	 * @param array $otros Array que contiene las propiedades del elemento. Vacía por defecto.
	 * @return string Tag HTML.
	*/
	function linkRemoto($valor, $layer_id, $accion, $otros = array())
	{
		$vars = explode("?", $accion);
		$otros['href'] = "#";
		if($otros['confirmar'])
		{
			$otros['onclick'] = "if(confirm('" . $otros['confirmar'] . "')) { ajax_load('$layer_id', '$vars[0]', '$vars[1]'); }";
			unset($otros['confirmar']);
		}
		else
		{
			$otros['onclick'] = "ajax_load('$layer_id', '$accion')";
		}
		
		return tag("a", $otros, $valor);
	}
	
	/**
	 * Genera un link.
	 * @param string $valor Texto del link.
	 * @param string $link El Link.
	 * @param array $otros Array que contiene las propiedades del elemento. Vacía por defecto.
	 * @return string Tag HTML.
	*/
	function linkA($valor, $link, $otros = array())
	{
		$otros['href'] = $link;
		if ($otros['confirmar'])
		{
			$otros['href'] = "#";
			$otros['onclick'] = "if(confirm('" . $otros['confirmar'] . "')) { location.href='$link'; return false; } else { return false; }";
			$otros['confirmar'] = "";
		}
		
		return tag("a", $otros, $valor);
	}
	
	/**
	 * Genera un link Ajax sobre una imagen.
	 * @param string $src Src de la imagen como se especifica en la funcion imagen().
	 * @param string $layer_id Id del Layer(div) donde se cargará el resultado de ajax.
	 * @param string $accion Contenido get de la llamada. Ej: (a=usuarios/mostrar&id=1).
	 * @param array $otros Array que contiene las propiedades del elemento. Vacía por defecto.
	 * @return string Tag HTML.
	*/
	function imagenRemota($src, $layer_id, $accion, $otros = array())
	{
		return linkRemoto(imagen($src, $arr), $layer_id, $accion, $otros);
	}
	
	/**
	 * Genera un link sobre una imagen.
	 * @param string $src Src de la imagen como se especifica en la funcion imagen().
	 * @param string $link El Link.
	 * @param array $otros Array que contiene las propiedades del elemento. Vacía por defecto.
	 * @return string Tag HTML.
	*/
	function imagenA($src, $link, $otros = array())
	{
		return linkA(imagen($src), $link, $otros);
	}
	
	/**
	 * Genera tags <input> de forma genérica.
	 * @param string $tipo Type del input. Ejemplo: "text" para <input type="text">.
	 * @param string $nombre Valor de la propiedad name.
	 * @param string $valor Valor de la propiedad value. null por defecto.
	 * @param array $otros Array que contiene las propiedades del elemento. Vacía por defecto.
	 * @return string Tag HTML.
	*/
	function tagCampo($tipo, $nombre, $valor = null, $otros = array())
	{
		$otros["type"] = $tipo;
		$otros["name"] = $nombre;
		if (!$otros['id'])
		{
			$otros["id"] = $nombre;
		}
		$otros["value"] = $valor;
		
		return tag("input", $otros);
	}
	
	/**
	 * Genera un tag <input type="hidden">.
	 * @param string $nombre Valor de la propiedad name.
	 * @param string $valor Valor de la propiedad value. null por defecto.
	 * @param array $otros Array que contiene las propiedades del elemento. Vacía por defecto.
	 * @return string Tag HTML.
	*/
	function campoOculto($nombre, $valor = null, $otros = array())
	{
		return tagCampo("hidden", $nombre, $valor, $otros);
	}
	
	/**
	 * Genera un tag <input type="text">.
	 * @param string $nombre Valor de la propiedad name.
	 * @param string $valor Valor de la propiedad value. null por defecto.
	 * @param array $otros Array que contiene las propiedades del elemento. Vacía por defecto.
	 * @return string Tag HTML.
	*/
	function campoTexto($nombre, $valor = null, $otros = array())
	{
		return tagCampo("text", $nombre, $valor, $otros);
	}
	
	/**
	 * Genera un tag <textarea></textarea>.
	 * @param string $nombre Valor de la propiedad name.
	 * @param string $valor Contenido del tag. null por defecto.
	 * @param array $otros Array que contiene las propiedades del elemento. Vacía por defecto.
	 * @return string Tag HTML.
	*/
	function campoAreaDeTexto($nombre, $valor = null, $otros = array())
	{
		$otros['name'] = $nombre;
		if (!$valor) { $valor = " "; }
		return tag("textarea", $otros, $valor);
	}
	
	/**
	 * Genera un tag <input type="password">.
	 * @param string $nombre Valor de la propiedad name.
	 * @param string $valor Valor de la propiedad value. null por defecto.
	 * @param array $otros Array que contiene las propiedades del elemento. Vacía por defecto.
	 * @return string Tag HTML.
	*/
	function campoPassword($nombre, $valor = null, $otros = array())
	{
		return tagCampo("password", $nombre, $valor, $otros);
	}
	
	/**
	 * Genera un tag <select>.
	 * @param string $nombre Valor de la propiedad name.
	 * @param string $valor Valor elegido por defecto dentro del select.
	 * @param array $valores Array donde las keys corresponden a la propiedad value, y el valor corresponde al contenido de los tags <option> del <select>.
	 * @param array $otros Array que contiene las propiedades del elemento. Vacía por defecto.
	 * @param array $otros2 Array que contiene las propiedades de los elementos <option>. Vacía por defecto.
	 * @return string Tag HTML.
	*/
	function campoSeleccion($nombre, $valor, $valores, $otros = array(), $otros2 = array())
	{
		$otros['name'] = $nombre;	
		return tag("select", $otros, camposOpciones($valores, $valor, $otros2));
	}
	
	/**
	 * Genera una lista de tags <option>.
	 * @param array $valores Array donde las keys corresponden a la propiedad value, y el valor corresponde al contenido de los tags <option> del <select>.
	 * @param string $valor Valor de la <option> elegida por defecto (selected='selected').
	 * @param array $otros Array que contiene las propiedades del elemento. Vacía por defecto.
	 * @param array $otros2 Array que contiene las propiedades de los elementos <option>. Vacía por defecto.
	 * @return string Tag HTML.
	*/
	function camposOpciones($valores, $valor = null, $otros = array())
	{
		foreach ($valores as $k => $v)
		{
			if ($k == $valor)
			{
				$otros['selected'] = 'selected';
			}
			else
			{
				unset($otros['selected']);
			}
			$otros['value'] = $k;
			$r .= tag("option", $otros, $v);
		}
		return $r;
	}
	
	/**
	 * Genera los tags <input> necesarios para requerir una fecha al usuario.
	 *
	 * Se generarán 4 variables al hacer un submit de estos tags. [nombre]_dia, [nombre]_mes, [nombre]_año y !RagDate_[nombre].
	 * Si el [nombre] contiene los símbolos [ o ], estos serán substituidos por _.
	 * @param string $nombre Nombre por el cual se referirá a esta fecha.
	 * @param time $valor Valor de la propiedad value. null por defecto.
	 * @param array $otros Array que contiene las propiedades del elemento. Vacía por defecto.
	 * @return string Tag HTML.
	*/
	function campoFecha($nombre, $valor = null, $otros = array())
	{
		$meses = mesesArray();
		$dias = array();
		for($i = 1; $i <= 31; $i++) { $dias[$i] = $i; }
		
		if ($valor == null) { $valor = date("Y-m-d"); }
		$vals = explode("-", $valor);
		$prename = str_replace(array("[", "]"), array("_", ""), $nombre);
		return campoSeleccion($prename. "_dia", $vals[2], $dias) . campoSeleccion($prename. "_mes", $vals[1], $meses) . campoTexto($prename. "_ano", $vals[0], array("style" => "width: 50px;")) . campoOculto("!RagDate_" . $prename, $nombre);  
	}
?>