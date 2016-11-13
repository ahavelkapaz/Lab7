<?php
	require_once 'db_config.php';
	session_start();
	
	 
	 $sid=$_SESSION['sid'];
	 $sql4="DELETE FROM users_online WHERE sid='$sid'"; 
	 $result4=mysqli_query($conn,$sql4);
	 
	$_SESSION['loggedin']=false;
	unset ($_SESSION['user']);
	unset ($_SESSION['sid']);
	unset ($_SESSION['loggedin']);
	unset ($_SESSION['rol']);
	unset ($_SESSION['start']);
	unset ($_SESSION['expire']);

	session_destroy();
	session_regenerate_id();
	
	header('Location: layout.php');

	?>