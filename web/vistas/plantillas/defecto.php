<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>¡Bienvenido a Ragnajag!</title>
	<meta name="description" content="Ragnajag - Página de Bienvenida" />
	<meta name="keywords" content="" />
	
	<?= Ops\favicon("ragnajag.ico"); ?>
	
	<?= Ops\stylesheet("ragnajag.css"); ?>
	<?= Ops\stylesheet("web/web.css"); ?>
</head>
<body>
	<h1>¡Bienvenido a Ragnajag!</h1>
	
	<div class="main">
		<?= Rag::$flash->dump(); ?>
		
		<? require Rag::$operador->rutaContenido(); ?>
	</div>
	<br/>
	<center><?= Ops\imagen("ragnajag.gif", array("align" => "center")); ?></center>
</body>
</html>
