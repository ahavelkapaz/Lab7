<?php

session_start();
require_once 'db_config.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {

	$now = time();

	if($now > $_SESSION['expire']) {
	session_destroy();
	$sql4="DELETE FROM users_online WHERE sid='$sid'"; 
	 $result4=mysqli_query($conn,$sql4);
	echo "Su sesion a terminado,
	<a href='layout.php?op=login'>Necesita Hacer Login</a><br>";
	echo '<a href="layout.php">Pagina de Inicio</a>'; 
	
		include "logout.php";
	exit();
	}
	else{
		
		$_SESSION['expire'] = time() + (10 * 60);//Hay actividad,volvemos a los 10 minutos de la sesion
		$time=time();
		//Actualizamos para usuarios online
		$session=session_id();
		$sql2="UPDATE users_online SET time='$time' WHERE sid = '$session'"; 
		$result2=mysqli_query($conn,$sql2); 
	}
} else {
   echo "Esta pagina es solo para usuarios registrados.<br>";
   echo "<br><a href='layout.php?op=login'>Login</a>";
   echo "<br><br><a href='layout.php?op=registrar'>Registrarme</a>";

exit();
}

?>