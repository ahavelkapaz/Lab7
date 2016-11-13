<?php


session_start();
 
 
 function registrarLog($accion) {

$action_log=$accion;

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
		include 'db_config.php';
		if(!mysqli_query($conn,$sql)){
		die('Error' . mysqli_error($conn));
		}
		

}
		
?>