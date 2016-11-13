<?php

	require_once 'db_config.php';
	include 'checklogin.php';
	
	$option=trim($_REQUEST['op']);
	
	if($option=="borrar"){ //Borrar Pregunta

	 $id_borrar=trim($_REQUEST['id']);
	 
	 $sql2="DELETE FROM quiz_questions WHERE id_quiz='$id_borrar'";
	 if(!mysqli_query($conn,$sql2)){
		die('Error borrar' . mysqli_error($conn));
	}
	else{ 
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
	else if($option=="numeropreguntas"){
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
	 
	mysqli_close($conn);




?>