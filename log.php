<?php

$current_file_name = basename($_SERVER['PHP_SELF']);
$action_log="";
$sid="";

session_start();

if($current_file_name=="verPreguntas.php"){
		
		$action_log="ver preguntas";
}
else if($current_file_name=="insertarPregunta.php" || $current_file_name=="functions.php"){
	$action_log="insertar preguntas";
}
else{
	echo $current_file_name . ' Error en log, desde donde llamas?';
	exit();
}

  if (!empty($_SERVER['HTTP_CLIENT_IP']))
            $ip= $_SERVER['HTTP_CLIENT_IP'];
           
      else  if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip= $_SERVER['HTTP_X_FORWARDED_FOR'];
       
      else  $ip= $_SERVER['REMOTE_ADDR'];
		
	$time=date("Y-m-d H:i:s");
	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
	$sid=session_id();
	else $sid="";
	$user=$_SESSION['user'];
		$sql="INSERT into actions VALUES(0,'$sid','$user','$action_log','$time','$ip')";
		if(!mysqli_query($conn,$sql)){
		die('Error' . mysqli_error($conn));
		}
		
		
?>