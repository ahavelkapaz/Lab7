<?php

	require_once 'db_config.php';
	//include 'checklogin.php';
	include 'log_f.php';
	
	$option=trim($_REQUEST['op']);
	
	if($option=="borrar"){ //Borrar Pregunta

	 $id_borrar=trim($_REQUEST['id']);
	 
	 $sql2="DELETE FROM quiz_questions WHERE id_quiz='$id_borrar'";
	 if(!mysqli_query($conn,$sql2)){
		die('Error borrar' . mysqli_error($conn));
	}
	else{ 
		registrarLog("Borrar pregunta" .$id_borrar);
		echo '<h5> Has borrado la pregunta ' . $id_borrar . ' con exito</h5>';
	}
	}
	else if($option=="complejidad"){//Modificar complejidad
	
	$id_q=trim($_REQUEST['id']);
	$rate=trim($_REQUEST['rate']);
	 
	 $sql2="UPDATE quiz_questions SET rate='$rate' WHERE id_quiz='$id_q'";
	 if(!mysqli_query($conn,$sql2)){
		die('Error actualizar' . mysqli_error($conn));
	}
	else{ 
		echo '<h5> Has actualizado la complejidad de ' . $id_q . ' a ' . $rate . '</h5>';
	}
		
	}
		else if($option=="actualizar"){//Modificar complejidad
	
	$id_q=trim($_REQUEST['id']);
	$rate=trim($_REQUEST['complejidad']);
	$question=$_REQUEST['pregunta'];
	$answer=$_REQUEST['respuesta'];
	 
	 $sql2="UPDATE quiz_questions SET rate='$rate',quiz_question='$question',quiz_answer='$answer' WHERE id_quiz='$id_q'";
	 if(!mysqli_query($conn,$sql2)){
		die('Error actualizar' . mysqli_error($conn));
	}
	else{ 
		registrarLog("Modificada pregunta" .$id_q);
		echo "<h5> Has actualizado la pregunta Id=" . $id_q . "<br>Pregunta=" . $question . "<br>Respuesta=" . $answer . "<br>Complejidad=" . $rate . "</h5>";
	}
		
	}
	else if ($option=="cambiarPass"){
		$pass=$_REQUEST['pass1'];
		$usuario=$_REQUEST['email'];
			if(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[$@$!%*#?&]).{8,16}$/',$pass)){
		die('La contraseña recibida no se ajusta al formato establecido. :)<br>');
			}
				if(!preg_match('/^([a-zA-Z]{2,})\d{3}@(ikasle\.){0,1}ehu\.(eus|es)$/',$usuario)){
		die('El correo electrónico recibido no se ajusta al formato establecido.:)<br>');
	}
		$pass=sha1($pass);
		 $sql2="UPDATE users SET password='$pass' WHERE email='$usuario'";
			if(!mysqli_query($conn,$sql2)){
				die('Error actualizar' . mysqli_error($conn));
			}
			else{
				echo 'Tu password ha sido cambiado,recuerdalo bien';
			}
		
		
	}
	else if($option=="numeropreguntas"){ //obtener numero de preguntas de usuario y totales
			$user=$_SESSION['user'];
			$sql="SELECT * FROM quiz_questions";
			$sql2="SELECT * FROM quiz_questions WHERE user_email='$user'";
			if($result=mysqli_query($conn,$sql)){
				$numtotal=mysqli_num_rows($result);
			}
			if($result=mysqli_query($conn,$sql2)){
				$numuser=mysqli_num_rows($result);
			}
			echo 'Mis preguntas/Todas las preguntas: [ ' .$numuser . '/' . $numtotal . ' ]';
	}
	else if($option=="emailSOAP"){
		
		ini_set('max_execution_time', 120);//Php tiempo de ejecucion aumentado
		
	require_once('../lib/nusoap.php');
	require_once('../lib/class.wsdlcache.php');
		
	$user_email2=$_REQUEST['email'];
	$soapclient = new nusoap_client('http://cursodssw.hol.es/comprobarmatricula.php?wsdl',true);
	$soapclient->response_timeout = 120;//aumentamos timeout en nusoap,tambien cambiado en nusoap.php...
	
	$start = time();
	$resp2=$soapclient->call('comprobar',array( 'x'=>$user_email2));
	$timing = time() - $start;
//Debug
//echo '<h2>Request</h2><pre>' . htmlspecialchars($soapclient->request, ENT_QUOTES) . '</pre>';
//echo '<h2>Response</h2><pre>' . htmlspecialchars($soapclient->response, ENT_QUOTES) . '</pre>';
//echo '<h2>Debug</h2>';
//echo '<pre>' . htmlspecialchars($soapclient->debug_str, ENT_QUOTES) . '</pre>';
	
	
	if($resp2=='NO'){
		die('El usuario no se encuentra matriculado en SistemasWeb<img src="img/wrong.png">');
	}
	else if($resp2=='SI'){
		echo'Usuario matriculado <img src="img/right.png">';
	}
	else{
		echo 'T=' .$timing	. 'segundos Connection timed out, Demasiado lenta la peticion' . $resp2;
	}
	}
	else if($option=="passSOAP"){
		
		require_once('../lib/nusoap.php');
		require_once('../lib/class.wsdlcache.php');
		$user_pass=$_REQUEST['pass'];
		$ticket=$_REQUEST['ticket'];
		//$soapclient = new nusoap_client('http://' . $_SERVER['SERVER_NAME'] . '/lab6/ComprobarPassword.php?wsdl',false);
		$soapclient = new nusoap_client('http://ahavelkapaz.esy.es/lab6/ComprobarPassword.php?wsdl',false);
	
		$resp=$soapclient->call('comprobarPass',array( 'x'=>$user_pass,'y'=>$ticket));
		if($resp=='INVALIDA'){
		die('El Password se encuentra entre los mas usados, escoge otro<div id=');
	}
		else if($resp=='USUARIO NO AUTORIZADO'){
			die('USUARIO NO AUTORIZADO<img src="img/wrong.png">');
		}
		else  if($resp=='VALIDA'){
		echo 'Password no común <img src="img/right.png" >';
	}
	else {
		echo $resp;
	}
		
	}
	else if($option=="insertarPregunta"){ //insertar pregunta en BD y XML
		
	$user_email = $_SESSION['user'];
	$user_quiz = $_REQUEST['question'];
	$user_answer = htmlspecialchars($_REQUEST['answer']);
	$user_rate = $_REQUEST['selectrate'];
	$user_subject=$_REQUEST['subject'];
	if((strlen($user_quiz)<1) || (strlen($user_answer)<1) || (strlen($user_subject)<1))
		die('Rellena todos los campos');
	
	$time=date("Y-m-d H:i:s");
	$sql="INSERT INTO quiz_questions VALUES(0,'$user_email','$user_quiz','$user_answer','$user_rate','$time','$user_subject')";

	if(!mysqli_query($conn,$sql)){
			die('Error' . mysqli_error($conn));

	}
	else{ 
		//Insercion en preguntas.xml
		if (file_exists('preguntas.xml')) {
		$preguntas=simplexml_load_file("preguntas.xml");
		//<assessmentItem>
		$pregunta = $preguntas->addChild('assessmentItem','');
			$pregunta->addAttribute('complexity',$user_rate);
			$pregunta->addAttribute('subject',$user_subject);
				//<itemBody>
				$item=$pregunta->addChild('itemBody');
					$item->addChild('p');
					$item->p=$user_quiz;
				//<correctResponse>
				$answer=$pregunta->addChild('correctResponse');
					$answer->addChild('value');
					$answer->value=$user_answer;
		$preguntas->asXML('preguntas.xml');
		}
		else{
			echo 'Error insertando en XML, Fichero no encontrado';
		}

		registrarLog("ver preguntas");
		echo '<h4>Pregunta registrada con Exito</h4>';
		
	}
	}
	 
	mysqli_close($conn);




?>