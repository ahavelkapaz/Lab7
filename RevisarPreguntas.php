<?php
include 'checklogin.php';
if($_SESSION['rol']!='profesor'){
   echo "<br><h3>Esta página es solo para profesores y tu eres " . $_SESSION['rol'] . "</h3>";
   echo "<br><br><a href='layout.php'>Volver a Home</a>";

exit();
}

?>
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
			$("#mensaje").hide();
			$("#mensaje").html(data).slideDown("slow");
			
			}).fail(function(response) {
				alert('Error borrar: ' + response.responseText);});
  
		});//end click
		
			
			$('.table-update').click(function () {
			$(this).parents('tr').css("background-color","#C3C600");
			var lista=new Array();
			var id_q=$(this).closest('tr').find('td:first-child').text();
			var preg= $(this).closest('tr').find("input:text,select").each(function() { 
				lista.push(this.value);//alert(this.value)
			});	

			//$("#mensaje").html("<h3>"+lista[0]+"<br>"+lista[1]+"<br>"+lista[2]+"</h3>");
			
			
			
			
			$.post("functions.php", { id: id_q,pregunta:lista[0],respuesta:lista[1],complejidad:lista[2],op: "actualizar"}, function(data) {
			$("#mensaje").hide();
			$("#mensaje").html(data).slideDown("slow");
			
			}).fail(function(response) {
				alert('Error borrar: ' + response.responseText);});
			
		});//end click
		
			/*$('select').on('change',function(){
			var id_q=$(this).closest('tr').find('td:first-child').text();
			$.post("functions.php", { id: id_q,op: "complejidad",rate:$(this).val()}, function(data) {//basename($_SERVER['PHP_SELF'])
			
			$("#mensaje").html(data);
			
			}).fail(function(response) {
				alert('Error actualizar: ' + response.responseText);});
		});//end select*/
		
		
		});//end ready
		</script>
<a href="layout.php?op=home" class="btn btn-info"><span class="glyphicon glyphicon-home"></span> Volver a Home</a>

<?php
	include "log_f.php";
	require_once 'db_config.php';

		session_start();
		if($_SESSION['rol']=='')$_SESSION['rol']='Anonimo';
	   echo '<div id="mensaje" style="background-color: rgba(164, 157, 157, 0.5)"> Tipo Usuario= ' . $_SESSION['rol'] . '</div>';
	 
	//Mostrar Preguntas   
	$sql="SELECT * FROM quiz_questions";
	$result = mysqli_query($conn,$sql);

	if ($result) 
	{
		echo '<table class="table table-bordered" > <tr><th>Id Pregunta:</th> <th>Email Id:</th><th>Pregunta</th><th>Respuesta</th><th>Dificultad</th><th>Fecha Añadida</th><th>Actualizar</th><th>Borrar</th></td>';
		while ($row = mysqli_fetch_array( $result )) {
			echo '<tr> <td>' . $row['id_quiz'] .'</td><td>' . $row['user_email'] . '</td><td>';
			echo "<input type='text' id='pregunta' class='pregunta' size='50' value='" . $row['quiz_question'] ."'> </td><td>";
			echo "<input type='text' id='respuesta' class='respuesta' size='15' value='" . $row['quiz_answer'] ."'> </td><td>";
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
				   
				   echo '<td><span class="table-update glyphicon glyphicon-floppy-open"></span></td>';
				   echo '<td><span class="table-remove glyphicon glyphicon-remove"></span></td>';
		
			}

		}
		echo '</table>';
		
		registrarLog("ver preguntas");
	} else {
		echo "Vacio";
	}

	mysqli_close($conn);
	echo "<br><br><a href='G.php'>Inserta más preguntas</a><br>";
	echo "<br><a href='layout.php?op=home'>...o vuelve a la página principal</a>";

?>
