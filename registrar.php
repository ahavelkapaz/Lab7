<?php

	require_once 'db_config.php';

	$user_name = $_POST['nombre'];
	$user_email = trim($_POST['email']);
	$user_rol = htmlspecialchars($_POST['selectrol']);
	$user_pass = trim($_POST['password']);
	$user_phone = trim($_POST['telefono']);
	$user_speciality = htmlspecialchars($_POST['selectoptions']);

	if($user_speciality=='Otros'){
		$user_speciality = htmlspecialchars($_POST['hideotros']);
	}
	$user_tech= htmlspecialchars($_POST['tech']);
	  
	if(!preg_match('/^([a-zA-Z]+)\s([a-zA-Z]+)\s{0,1}([a-zA-Z]*)$/',$user_name)){
		die('El nomre recibido no se ajusta al formato establecido. <br><br><a href="registro.html">Vuelve a registrarte</a>');
	}
	if(!preg_match('/^([a-zA-Z]{2,})\d{3}@(ikasle\.){0,1}ehu\.(eus|es)$/',$user_email)){
		die('El correo electrónico recibido no se ajusta al formato establecido. <br><br><a href="registro.html">Vuelve a registrarte</a>');
	}
	if(!preg_match('/^(\+[0-9]{2}){0,1}[0-9]{9,25}$/',$user_phone)){
		die('El número de teléfono recibido no se ajusta al formato establecido. <br><br><a href="registro.html">Vuelve a registrarte</a>');
	}
	if(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[$@$!%*#?&]).{8,16}$/',$user_pass)){
		die('La contraseña recibido no se ajusta al formato establecido. <br><br><a href="registro.html">Vuelve a registrarte</a>');
	}
	
	//Comprobacion usuario registrado en SW mediante SOAP
	
	require_once('../lib/nusoap.php');
	require_once('../lib/class.wsdlcache.php');

	$soapclient = new nusoap_client('http://sw14.hol.es/ServiciosWeb/comprobarmatricula.php?wsdl',false);
	if($soapclient->call('comprobar',array( 'x'=>$user_email))=='NO'){
		die('El usuario no se encuentra matriculado en SistemasWeb Registro denegado');
	}
	//Comprobacion password no esta entre los mas usados del txt mediante SOAP
	$soapclient = new nusoap_client('http://' . $_SERVER['SERVER_NAME'] . '/lab6/ComprobarPassword.php?wsdl',false);
	
		if($soapclient->call('comprobarPass',array( 'x'=>$user_pass))=='INVALIDA'){
		die('El Password se encuentra entre los mas usados, escoge otro');
	}
	
	$user_pass=sha1($user_pass);//Encriptamos SHA1

	
	
	$image="";
	//Añadir imagen
	if($_FILES['image']['size']>0)
	{
		$image= mysqli_real_escape_string($conn, file_get_contents($_FILES['image']['tmp_name']));
	}
	else
	{
		$image = mysqli_real_escape_string($conn,file_get_contents("avatar.jpg"));
	}
	
	
	$time=date("Y-m-d H:i:s");
	
	$sql="INSERT INTO users(name,email,password,phone,department,tech_tools,avatar,date,role) VALUES('$user_name','$user_email','$user_pass','$user_phone','$user_speciality','$user_tech','$image','$time','$user_rol')";
	
	/*$sql_check_mail="SELECT * from users where email='$user_email'";

	$result = mysqli_query($conn,$sql_check_mail);
	if ($result.count()>0) {
		echo 'Error usuario registrado';
	}
	else{*/

	if(!mysqli_query($conn,$sql)){
		die('Error' . mysqli_error($conn));
		die('Error Usuario ya Registrado');
	}
	else{ 
		echo'Te has registrado con exito!<br>';
		echo 'Logueate <a href="login.php">Login</a><br>';
		echo "<a href='verUsuarios.php'>Ver Usuarios Registrados</a>";
	}

	//}

	mysqli_close($conn);



?>