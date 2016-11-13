<?php
include 'checklogin.php';
include "log_f.php";
?>


<head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>

<body>
<form name="fregquiz" id="fregquiz" class="form-horizontal" method="POST" action="">
<fieldset>

<!-- Form Name -->
<legend>Quiz Registration</legend>

<!-- Text input-->
<div class="form-group">
 
  <h3><?php echo '- Hola ' . $_SESSION['user'] . '. Ingresa tu pregunta a registrar'; ?></h3>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="pregunta">Pregunta</label>  
  <div class="col-md-6">
  <input id="question" name="question" placeholder="?" class="form-control input-md" required="" type="text">
  <span class="help-block">Pregunta a registrar</span>  
  </div>
</div>
<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="answer">Tema</label>
  <div class="col-md-4">                     
    <input id="subject" name="subject" placeholder="Tema" class="form-control input-md" required="" type="text">
  </div>
</div>
<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="answer">Respuesta</label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="answer" placeholder="-" name="answer"> </textarea>
  </div>
</div>



<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="selectrate">Complejidad</label>
  <div class="col-md-5">
    <select id="selectrate" name="selectrate" class="form-control">
      <option value="0">Sin Puntuar</option>
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
    </select>
  </div>
</div>

<!-- Button (Double) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="buttonreset"></label>
  <div class="col-md-8">
    <input type="reset" id="buttonreset" name="buttonreset" class="btn btn-danger" value="Restablecer">
	<input type="submit" id="registrarB" name="registrarB" value="Registrar Pregunta" class="btn btn-success">
  </div>
</div>
</fieldset>
</form>
</body>

<?php
	
if(isset($_REQUEST['question'])){

	require_once 'db_config.php';

	$user_email = $_SESSION['user'];
	$user_quiz = $_REQUEST['question'];
	$user_answer = htmlspecialchars($_REQUEST['answer']);
	$user_rate = htmlspecialchars($_REQUEST['selectrate']);
	$user_subject=$_REQUEST['subject'];
	
	if (!filter_var($user_email, FILTER_VALIDATE_EMAIL) === false) {
			echo '';
			} else {
				die("$user_email is not a valid email address");
		}
$time=date("Y-m-d H:i:s");
$sql="INSERT INTO quiz_questions VALUES(0,'$user_email','$user_quiz','$user_answer','$user_rate','$time','$user_subject')";

if(!mysqli_query($conn,$sql)){
		die('Error' . mysqli_error($conn));

	}
	else{ 
		//Insercion en preguntas.xml
		if (file_exists('preguntas.xml')) {
		$preguntas=simplexml_load_file("preguntas.xml");
		//echo 'XML=' . $preguntas->asXML();
		//<assessmentItem>
		$pregunta = $preguntas->addChild('assessmentItem','');
			$pregunta->addAttribute('complexity',$user_rate);
			$pregunta->addAttribute('subject',$user_subject);//subject?	/ xpath en el obligatorio 2 /examen perl,asp etc? /
				//<itemBody>
				$item=$pregunta->addChild('itemBody');
					$item->addChild('p');
					$item->p=$user_quiz;
				//<correctResponse>
				$answer=$pregunta->addChild('correctResponse');
					$answer->addChild('value');
					$answer->value=$user_answer;
		$preguntas->asXML('preguntas.xml');
		}
		else{
			echo 'Error insertando en XML, Fichero no encontrado';
		}

		//Log de insercion
		registrarLog("insertar pregunta");
		echo '<h4>Pregunta registrada!</h4><br>';
		echo "<a href='verPreguntasXML.php'>Ver Preguntas(XML)</a><br>";
		echo "<a href='verPreguntas.php'>Ver Preguntas(DB)</a>";
	}

	//}

	mysqli_close($conn);

}
echo '<br><br><a href="layout.php">Vuelve a la pagina principal</a><br><br>';
?>
