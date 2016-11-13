<?php
		session_start();

	if(isset($_SESSION['loggedin'])) {
		$user=$_SESSION['user'];
		$rol=$_SESSION['rol'];
			}
			else{
		$user='anonimo';
		$rol='anonimo';
			}
	
	$op='home';
	if(isset($_REQUEST['op']))
		$op=$_REQUEST['op'];
?>
<html>
  <head>
    <meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	<title>Preguntas</title>

    <link rel='stylesheet' type='text/css' href='estilos/style.css' />
	<link rel='stylesheet' 
		   type='text/css' 
		   media='only screen and (min-width: 530px) and (min-device-width: 481px)'
		   href='estilos/wide.css' />
	<link rel='stylesheet' 
		   type='text/css' 
		   media='only screen and (max-width: 480px)'
		   href='estilos/smartphone.css' />
		   		<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

		  	<script src="js/jquery-3.1.1.min.js"></script>
			<script>
			$(document).ready(function(){
				
				var x=$('#userh p').text();
				var r=$('#roleh p').text();

			if(x=='anonimo'){
				
				$('#logged').hide();
				$('#notlogged').show();
			}
			else{
	
				$('#notlogged').hide();
				$('#logged').show();
				if($rol=='alumno'){
				
					$('.profesor').hide();
					$('.alumno').show();
				}
				else if($rol=='profesor'){
			
					$('.alumno').hide();
					$('.profesor').show();
				}
			}
			
			});
			
			</script>
		 <!--	<script> 
			$(document).ready(function(){
				$("a#h_login").on('click', function(e){
					e.preventDefault(); 
					$("#contenido").load("login.php", function(response, status, xhr) {});
				}); 
								$("a#h_register").on('click', function(e){
					e.preventDefault(); 
					$("#contenido").load("registro.html", function(response, status, xhr) {});
				}); 
						$("a#h_logout").on('click', function(e){
					e.preventDefault(); 
					$("#contenido").load("logout.php", function(response, status, xhr) {});
				}); 
								$("a#h_questions").on('click', function(e){
					e.preventDefault(); 
					$("#contenido").load("verPreguntas.php", function(response, status, xhr) {});
				}); 
						$("a#h_credits").on('click', function(e){
					e.preventDefault(); 
					$("#contenido").load("creditos.html", function(response, status, xhr) {});
				}); 
			});
			</script> -->
  </head>
  <body>
  <div id='page-wrap'>
	<header class='main' id='h1'>
		<div id="userh" hidden><p><?php echo $user; ?></p></div>
		<div id="roleh" hidden><p><?php echo $rol; ?></p></div>
		<span class="right"><a id="h_register" href="layout.php?op=registrar">Registrarse</a></span>
			<div id="notlogged">
      		<span class="right"><a id="h_login" href="layout.php?op=login">Login</a></span>
			</div>
			<div id="logged">
			<p id="saludo">Hola <?php echo $user; ?></p>
			<span class="right"><a id="h_logout" href="logout.php">Logout</a></span>
      		<span class="right" style="display:none;">
			</div>
		<h2>Quiz: el juego de las preguntas</h2>
    </header>
	<nav class='main' id='n1' role='navigation'>
		<span><a href='layout.php?op=home'>Inicio</a></spam>
		<span><a id="h_questions" href='layout.php?op=verPreguntas'>Ver preguntas</a></span>
		<span><a id="h_gestionP" class="alumno" href='GestionPreguntas.php'>Gestion preguntas</a></span>
		<span><a id="h_revisionP" class="profesor" href='RevisarPreguntas.php'>Revision preguntas</a></span>		
		<span><a id="h_users" href='layout.php?op=verUsuarios'>Ver Usuarios</a></span>
		<span><a id="h_credits" href='creditos.html'>Creditos</a></span>
	</nav>
    <section class="main" id="s1">
    
	<div id="contenido" name="contenido">
	<?php 
	if($op=='home'){
		
	}
	else if($op=='verPreguntas'){
		include 'verPreguntas.php';
	}
	else if($op=='login'){
		include 'login.php';
	}
	else if($op=='verUsuarios'){
		include 'verUsuarios.php';
	}
	else if($op=='registrar'){
		include 'registro.html';
	}
	else if($op=='gestionPreguntas'){
	//include 'GestionPreguntas.php';
		header("Location: GestionPreguntas.php");
	}
		else if($op=='revisionPreguntas'){
	//include 'GestionPreguntas.php';
		header("Location: RevisarPreguntas.php");
	}
	else if($op=='mensaje'){
		echo $_REQUEST['m'];
	}
		
	else{
		echo 'Te has perdido :) <a href="layout.php?op=home">Vuelve a Home</a>';
	}
	?>
	</div>
		<div class='main' id='f1'>
		<p><a href="http://es.wikipedia.org/wiki/Quiz" target="_blank">Que es un Quiz?</a></p>
		<a href='https://github.com/ahavelkapaz/'>Link GITHUB</a>
	</div>
    </section>

</div>
</body>
</html>

