<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


<?php
	require_once 'db_config.php';

	
	   
	$sql="SELECT * FROM quiz_questions";
	$result = mysqli_query($conn,$sql);

	if ($result) {
		echo '<table class="table table-bordered" > <tr><th>Id Pregunta:</th> <th>Email Id:</th><th>Pregunta</th><th>Respuesta</th><th>Puntuacion</th><th>Fecha Añadida</th></td>';
		while ($row = mysqli_fetch_array( $result )) {
			echo '<tr> <td>' . $row['id_quiz'] .'</td><td>' . $row['user_email'] . '</td><td>' . $row['quiz_question'] . '</td><td>' . $row['quiz_answer'] . '</td><td>' . $row['rate'] . '</td><td>' . $row['date'] .'</td>'; 


		}
		echo '</table>';
		include "log.php";
	}
	else{
		echo "Vacio";
	}

	mysqli_close($conn);

?>
