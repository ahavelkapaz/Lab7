<?php 	
include 'checklogin.php';
if($_SESSION['rol']!='alumno'){
	header("Location: layout.php?op=mensaje&m=Esta página es solo para Alumnos y tu eres " . $_SESSION['rol']."");
   echo "<br><h3>Esta página es solo para Alumnos y tu eres " . $_SESSION['rol'] . "</h3>";
   echo "<br><br><a href='layout.php'>Volver a Home</a>";
exit();
   }

?>
<html>
<head>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	<script src="js/jquery-3.1.1.min.js"></script>

	<script>
	setInterval(getNumPreguntas,5000);
	var op="nada";

	XMLHttpRequestObject = new XMLHttpRequest();
	getNumPreguntas();//cargar al inicio los divs
	getNumPreguntasJQ();
	getOnlineUsers();
	XMLHttpRequestObject.onreadystatechange = function(){
		
		if (XMLHttpRequestObject.readyState==4)
		{
			var obj = document.getElementById('contenido');
			var obj2 = document.getElementById('contenido2');
			var objmsj = document.getElementById('mensaje');
			var objmsj2 = document.getElementById('myQuestions');
			if(op=="getPreguntas"){
				$('#contenido').hide();
				$('#contenido2').slideDown("slow");
				obj2.innerHTML = XMLHttpRequestObject.responseText;
				objmsj.innerHTML="<h4>Preguntas Cargadas del XML via AJAX</h4>";
			}
			else if(op=="getNumPreguntas"){
				objmsj2.innerHTML = XMLHttpRequestObject.responseText;

			}
			else if(op=="insertarPregunta"){
				objmsj.innerHTML=XMLHttpRequestObject.responseText;;
			}
		
			}
	}

	function getPreguntas()
	{
		//XMLHttpRequestObject.open("GET",'preguntas.xml'); 
		XMLHttpRequestObject.open("GET",'verPreguntasXML.php'); 
		XMLHttpRequestObject.send(null);
		op="getPreguntas";

	}

	function mostrarRegistro(){
		
		$('#contenido2').hide();
		$('#contenido').slideDown("slow");;
		$('#mensaje').html("<h4>Registra una Pregunta</h4>");
	}
	/*function estadoPeticion(){
		if(XMLHttpRequestObject.readyState<4){
				
		document.getElementById('myQuestions').innerHTML ="<img src='img/loading.gif' alt='Cargando...'>";
		
		}
		else{
		//Si ya hemos completado la petición, devolvemos además la información.
		//document.getElementById('txtHint').innerHTML=XMLHttpRequestObject.responseText;
		document.getElementById('myQuestions').innerHTML = XMLHttpRequestObject.responseText;
		}
	}*/
	//AJAX
	function getNumPreguntas(){
		
		XMLHttpRequestObject.open("POST","functions.php",true);
		XMLHttpRequestObject.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		XMLHttpRequestObject.send("op=numeropreguntas");
		op="getNumPreguntas";
	}
	//JQUERY
	function getNumPreguntasJQ(){
				$.post("functions.php", {op: "numeropreguntas"}, function(data) {
				
				$("#myQuestionsJQ").html(data);
				
				}).fail(function(response) {
					$("#mensaje").html('Error getNumPreguntasJQ = ' +response.responseText);
					});
					
	}

	function getOnlineUsers(){
						$.post("online_users.php", {}, function(data) {
				
				$("#onlineUsers").html(data);
				
				}).fail(function(response) {
					$("#mensaje").html('Error onlineUsers = ' +response.responseText);
					});
	}
	$(document).ready(function()
	{
		var refreshId = setInterval( getNumPreguntasJQ, 5000);
		var refreshId2 = setInterval( getOnlineUsers, 5000);
	});

	function insertarPregunta(){
		
	var q=document.getElementById('question').value;
	var a=document.getElementById('answer').value;
	var r=document.getElementById('selectrate').selectedIndex;
	var s=document.getElementById('subject').value;

	if(q.length<=1 || a.length<1 || s.length<1){
		alert("Rellena todos los campos");
		$('#mensaje').html('<h4>Para registrar una pregunta rellena todos los campos</h4>');;
		return;
	}

	XMLHttpRequestObject.open("POST","functions.php",true);
	XMLHttpRequestObject.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	XMLHttpRequestObject.send("op=insertarPregunta&question="+q+"&answer="+a+"&selectrate="+r+"&subject="+s);
	op="insertarPregunta";
	
	//borrar formulario
	document.getElementById('question').value = "";	
	document.getElementById('answer').value = "";
	document.getElementById('selectrate').selectedIndex = 0;
	document.getElementById('subject').value = "";
		
	}

	</script>
<a href="layout.php?op=home" class="btn btn-info"><span class="glyphicon glyphicon-home"></span> Volver a Home</a>
</head>
	<body>
	<div style="padding:40px 40px 0px 40px">
		<h4>Gestor Preguntas</h4>
		<div id="onlineUsers" style="background:yellow;padding:20px"><span class="glyphicon glyphicon-lock"></span></div>
		Ajax Version:
		<div id="myQuestions" style="background:lightblue"></div>
		Jquery Version: 
		<div id="myQuestionsJQ" style="background:lightblue"></div>
		<br><br>
		<input type="button" value="Edicion Pregunta" onClick="mostrarRegistro()" class="btn btn-default">
		<input type="button" value="Ver Preguntas" onClick="getPreguntas()" class="btn btn-default"><br><br>		
		<div id="mensaje" style="background:lightblue; margin-top:30px"><h4>Registra una Pregunta</h4></div>
	</div>

	<div id="contenido" style="padding:0px 40px 60px 40px">
		<!-- Form Name -->
		<legend>Quiz Registration</legend>

		<!-- Text input-->
		<div class="form-group">

			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-1 control-label" for="pregunta">Pregunta</label>  
			  <div class="col-md-4">
			  <input id="question" name="question" placeholder="?" class="form-control input-md" required type="text"> 
			  </div>
			</div>
			<br><br>
			<!-- Textarea -->
			<div class="form-group">
			  <label class="col-md-1 control-label" for="answer">Tema</label>
			  <div class="col-md-4">                     
				<input id="subject" name="subject" placeholder="Tema" class="form-control input-md" required type="text">
			  </div>
			</div>
			<br><br>
			<!-- Textarea -->
			<div class="form-group">
			  <label class="col-md-1 control-label" for="answer">Respuesta</label>
			  <div class="col-md-4">                     
				<textarea class="form-control" id="answer" placeholder="-" name="answer" required> </textarea>
			  </div>
			</div>
			<br><br>
			<!-- Select Basic -->
			<div class="form-group">
			<br><br>
			  <label class="col-md-1 control-label" for="answer" style="margin-right:15px; margin-top:7px">Complejidad</label>
				<select id="selectrate" name="selectrate" class="form-control" style="width:300px">
				  <option value="0">Sin Puntuar</option>
				  <option value="1">1</option>
				  <option value="2">2</option>
				  <option value="3">3</option>
				  <option value="4">4</option>
				  <option value="5">5</option>
				</select>
			</div>
		</div>
		<br>
		<!-- Button (Double) -->
		<div class="form-group">
		  <label class="col-md-0 control-label" for="buttonreset"></label>
		  <div class="col-md-1">
			<input type="submit" id="registrarB" onClick="insertarPregunta()" value="Registrar Pregunta" class="btn btn-success">
			
		  </div>
		</div>

	</div>
	<div id="contenido2"></div>
	<br><a href='layout.php'>Volver a Layout</a>
	</body>
</html>
