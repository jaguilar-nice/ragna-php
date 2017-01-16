<h2>Pruebas 0.7</h2>
<?
	class Usuario extends \ActiveRecord\Model
	{
		static $table_name = 'usuarios';
		
		// a person can have many orders and payments
		static $has_many = array(
			array('noticias')
		);
	}
	
	class Noticia extends \ActiveRecord\Model
	{
		static $table_name = 'noticias';
		
		// a person can have many orders and payments
		static $belongs_to = array(
			array('usuario')
		);
	}
	
	
	
	
	Ops\debug(print_r(Usuario::first()->noticias));
	/*
	echo "<hr>";
	foreach (Dag::$t->usuarios->encontrarPorId(1) as $us)
		Ops\debug($us->label());
	echo "<hr>";
	foreach (Dag::$t->usuarios->encontrarPorIdOLoginOLogin(2, "urbin", "tarsis") as $us)
		Ops\debug($us->label());
	*/
?>
