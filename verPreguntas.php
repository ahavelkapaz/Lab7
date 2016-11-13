<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script src="js/jquery-3.1.1.min.js"></script>
		<script>
		$(document).ready(function(){
			
			$('.table-remove').click(function () {

			var id_q=$(this).closest('tr').find('td:first-child').text();
			$(this).parents('tr').css("background-color","#FF3700");
			$(this).parents('tr').fadeOut(400, function(){
            $(this).parents('tr').remove();});
			
			$.post("functions.php", { id: id_q,op: "borrar"}, function(data) {//basename($_SERVER['PHP_SELF'])
			
			$("#mensaje").html(data);
			
			}).fail(function(response) {
				alert('Error borrar: ' + response.responseText);});
  
		});//end click
		
					 $('select').on('change',function(){
			var id_q=$(this).closest('tr').find('td:first-child').text();
			$.post("functions.php", { id: id_q,op: "complejidad",rate:$(this).val()}, function(data) {//basename($_SERVER['PHP_SELF'])
			
			$("#mensaje").html(data);
			
			}).fail(function(response) {
				alert('Error actualizar: ' + response.responseText);});
		});//end select
		
		
		});//end ready
		</script>

<?php
	include "log_f.php";
	require_once 'db_config.php';

		session_start();
		if(!isset($_SESSION['rol']) || $_SESSION['rol']=='')
			$_SESSION['rol']='Anonimo';
	   echo '<div id="mensaje"> Tipo Usuario= ' . $_SESSION['rol'] . '</div>';
	 
	//Mostrar Preguntas   
	$sql="SELECT * FROM quiz_questions";
	$result = mysqli_query($conn,$sql);

	if ($result) 
	{
		echo '<table class="table table-bordered" > <tr><th>Id Pregunta:</th> <th>Email Id:</th><th>Pregunta</th><th>Respuesta</th><th>Dificultad</th><th>Fecha Añadida</th></td>';
		while ($row = mysqli_fetch_array( $result )) {
			echo '<tr> <td>' . $row['id_quiz'] .'</td><td>' . $row['user_email'] . '</td><td>' . $row['quiz_question'] . '</td><td>' . $row['quiz_answer'] . '</td><td>';
			if ( $_SESSION['rol']=="profesor" && $_SESSION['loggedin'] == true){
			echo '<select id="selectrate" name="selectrate" class="form-control">
				  <option value="0"';if ($row['rate'] == "0") echo "selected='selected'";echo '>Sin Calificar</option>';
			echo  '<option value="1"';if ($row['rate'] == "1") echo "selected='selected'";echo '>1</option>
				  <option value="2"';if ($row['rate'] == "2") echo "selected='selected'";echo '>2</option>
				  <option value="3"';if ($row['rate'] == "3") echo "selected='selected'";echo '>3</option>
				  <option value="4"';if ($row['rate'] == "4") echo "selected='selected'";echo '>4</option>
				  <option value="5"';if ($row['rate'] == "5") echo "selected='selected'";echo '>5</option></select>';
			
			
			echo '</td><td>';
			}
			else echo $row['rate'] . '</td><td>';
			
			echo $row['date'] .'</td>'; 
			
			if ( $_SESSION['rol']=="profesor" && $_SESSION['loggedin'] == true){
				   echo '<td><span class="table-remove glyphicon glyphicon-remove"></span></td>';
		
			}

		}
		echo '</table>';
		
		registrarLog("ver preguntas");
	} else {
		echo "Vacio";
	}

	mysqli_close($conn);
	echo "<br><br><a href='insertarPregunta.php'>Inserta más preguntas</a><br>";
	echo "<br><a href='layout.php'>...o vuelve a la página principal</a>";

?>
