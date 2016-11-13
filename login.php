<?php
session_start();

	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
		echo 'Hola ' . $_SESSION['user'] . 'Sid= ' . session_id() . '! <br>';
		echo '<a href="verPreguntas.php">Ver preguntas</a><br>';
		echo '<a href="insertarPregunta.php">Registrar una nueva pregunta</a><br>';
		echo '<a href="logout.php">Cerrar Sesion</a>';
		exit();
	}

?>

<head>
<script src="js/jquery-3.1.1.min.js"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		
<script>
function nuevoPassDiv(){
	$('#nuevopass').show();
}
function nuevoPassword(){
	var npass1=document.getElementById('pass1').value;
	var npass2=document.getElementById('pass2').value;
	var correo=$('#correo').text();
	if(!document.getElementById('pass1').checkValidity() || !document.getElementById('pass2').checkValidity()){
		$("#nuevopass").html("<h4>Los password no se ajustan al Formato: <br>Mínimo => 1 mayúscula, 1 minúscula, 1 dígito y 1 Carácter especial. La longitud debe ser entre 8 y 16 caracteres.</h4>");
		exit();
	}
	if(npass1==npass2) {
	$.post("functions.php", {op:'cambiarPass',pass1:npass1,pass2:npass2,email:correo}, function(data) {
				
				$("#nuevopass").html(data);
				
				}).fail(function(response) {
					$("#nuevopass").html('Error cambiar pass = ' +response.responseText);
					});
					
	}
	else{
		$('#pass1').css("border", "2px solid red");
		$('#pass2').css("border", "2px solid red");
		$('#divmensaje').html("<h4>Las Contreseñas no coinciden</h4>");
	}
}

</script>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-sm-6">
			<div class="login">
				<div class="panel panel-default">
				  <div class="panel-heading"><span class="glyphicon glyphicon-user"></span> Acceso Usuarios</div>
					<div class="panel-body">
						<form class="form-inline" role="form" method="POST">
							 <div class="form-group">
								<label class="sr-only" for="Uemail">Email address</label>
								<input type="text" class="form-control" id="Uemail" name= "Uemail" placeholder="Email"	pattern ="([a-zA-Z]{2,})\d{3}@(ikasle\.){0,1}ehu\.(eus|es)" required title="El correo debe tener el formato de la UPV/EHU"">
							  </div>						
							 <div class="form-group">
								<label class="sr-only" for="Uemail">Password</label>
								<input type="password" class="form-control" id="Upassword" name= "Upassword" placeholder="Password" required>
							  </div>
							  <button type="submit" class="btn btn-success">Login</button>
						</form>						   
					</div>
				  </div>
				</div>
				<div id="nuevopass" style="background-color: rgba(164, 157, 157, 0.5)" hidden>
				
				<p>Introduce tu nueva Contraseña para la cuenta: <div id="correo"><?php echo $_REQUEST['Uemail'];?></div></p>
				<input type="password" id="pass1" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[$@$!%*#?&]).{8,16}" required
			title="Formato mínimo => 1 mayúscula, 1 minúscula, 1 dígito y 1 Carácter especial. La longitud debe ser entre 8 y 16 caracteres."><br>
				<input type="password" id="pass2" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[$@$!%*#?&]).{8,16}" required
			title="Formato mínimo => 1 mayúscula, 1 minúscula, 1 dígito y 1 Carácter especial. La longitud debe ser entre 8 y 16 caracteres."><br>
				<input type="button" id="bp" onclick="nuevoPassword()" value="Cambiar">
				
				</div><br>
				<div id="divmensaje"></div><br>
			</div>
		</div>
	</div>
</body>


<?php
require_once 'db_config.php';

if(isset($_REQUEST['Uemail'])){
	
//Recibimos las dos variables
$usuario=mysqli_real_escape_string($conn,$_REQUEST["Uemail"]);
$password=mysqli_real_escape_string($conn,sha1($_REQUEST["Upassword"]));

$users = mysqli_query($conn,"SELECT * FROM users WHERE email = '$usuario' AND password = '$password'");


if(mysqli_num_rows($users) > 0) 
{

	//Mirar bloqueado
	$resp = mysqli_query($conn,"SELECT bloqueado FROM users WHERE email = '$usuario' AND bloqueado='true'");
	$resp=mysqli_fetch_array($resp);
	//echo '(' . $resp[0] . ')';
	//exit();
	if($resp[0]=='true'){

			echo '<h4>Usuario bloqueado,Habla con el administrador </h4><img src="img/triste.svg">';
			exit();
		}
	
	
	
	//Borrar intentos
	$sql="UPDATE users SET intentos='0' WHERE email='$usuario'";
		 if(!mysqli_query($conn,$sql)){
		die('Error resetear intentos: ' . mysqli_error($conn));
		 }

	session_start();
	session_regenerate_id();
	
	
		$_SESSION['user']="$usuario";
		$_SESSION['loggedin'] = true;
		$row = mysqli_fetch_array( $users);
		$_SESSION['rol'] = $row['role'];
		$_SESSION['start'] = time();
		$_SESSION['expire'] = $_SESSION['start'] + (10 * 60);//minutos
		$_SESSION['sid']=session_id();
		echo "Bienvenido! " . $_SESSION['user'];
		
		//log login connection
		$time=date("Y-m-d H:i:s");
		$sid=session_id();
		$sql="INSERT into connections VALUES('$sid','$usuario','$time')";
		if(!mysqli_query($conn,$sql)){
		die('Error log conexion' . mysqli_error($conn));
		}
		
		mysqli_close($conn); 

		if($_SESSION['rol']=='profesor'){
			
			header("Location: RevisarPreguntas.php");
		}
		else if($_SESSION['rol']=='alumno'){
		header("Location: GestionPreguntas.php");

		}
		else
		header("Location: layout.php");
	exit(); 
}
 
else 
{	
	//si el usuario no existe
	$users = mysqli_query($conn,"SELECT * FROM users WHERE email = '$usuario'");
	if(mysqli_num_rows($users) < 1) 
	{
		echo 'El usuario no se encuentra registrado<br>';
		echo 'Registrate <a href="layout.php?op=registrar">Registrarme</a>';
		exit();
	}
	//si existe contamos intentos
	$intentos = mysqli_query($conn,"SELECT intentos FROM users WHERE email = '$usuario'");
	$intentos=mysqli_fetch_array($intentos);

	$intentos_final=$intentos[0]+1;

	$resp2 = mysqli_query($conn,"UPDATE users SET intentos='$intentos_final' WHERE email='$usuario'");
	//si llega a los 3 intentos erroneos le bloqueamos.
   if($intentos_final>=3){
	   $sql="UPDATE users SET bloqueado='true' WHERE email='$usuario'";

			 if(!mysqli_query($conn,$sql)){
		die('Error bloquear usuario: ' . mysqli_error($conn));
		 }
	   echo 'Usuario Bloqueado: Contacta con el administrador<br>';

   }else{
	  
	echo 'Numero de Intentos '.$intentos_final . ' de 3<br>';
	echo " El usuario o la contraseña son incorrectos, por favor vuelva a introducirlos.<br>";
   }
   
	echo '<a href="#" onclick="nuevoPassDiv()">Olvide Contraseña</a><br>';
   echo'<a href="registro.html">Registrate</a><br>'; 
   echo '<a href="layout.php">Vuelve a la pagina principal</a>'; 
}


mysqli_close($conn); 
}


		
?>