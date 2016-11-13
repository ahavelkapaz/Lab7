<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


<?php
	include 'checklogin.php';
	require_once 'db_config.php';
	   
	$sql="SELECT * FROM users";
	$result = mysqli_query($conn,$sql);

	if ($result) {
		echo '<table class="table table-bordered" > <tr> <th>Nombre y apellidos</th><th>E-mail</th><th>Teléfono</th><th>Fecha de registro</th><th>Especialidad</th><th>Tecnologías y herramientas de interés</th><th>Role</th><th>Foto de perfil</th></td>';
		while ($row = mysqli_fetch_array( $result )) {
			echo '<tr> <td>' . $row['name'] . '</td><td>' . $row['email'] . '</td><td>' . $row['phone'] . '</td><td>' . $row['date'] . '</td><td>' . $row['department'] . '</td><td>' . $row['tech_tools'] .'</td><td>'. $row['role'] .'</td>'; 
			echo '<td><img src="data:image/jpeg;base64,' . base64_encode($row['avatar']) . '" /></td></tr>';

		}
		echo '</table>';
	}
	else{
		echo "Vacio";
	}

	mysqli_close($conn);
	
	echo '<br> <a href="layout.php">Vuelve a la página principal</a>'

?>