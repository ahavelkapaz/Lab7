<?php


	$handle = fopen('toppasswords.txt', 'r');
	$encontrado = false;
	$x='asier';
	while (($buffer = fgets($handle)) !== false) {
		if (trim($buffer)==$x) {
			echo $buffer . '=' . $x .'?';
			$encontrado = true;
			break;
		}      
	}
	fclose($handle);
		echo '<br>Buscamos: ' . $x . ' <br>';
	if($encontrado)
	echo 'ENCONTRADO';
else echo 'NO ENCONTRADO';
?>
<!-- 
Es obligatorio usar coockies o se puede con la sesion 
cambio de contraseña mediante email reseteo con API Restful o formulario sinmas
3 intentos para entrar al sistema por sesion hay que controlar que sea el mismo usuario de sesion o a los 3 se bloquea?
destroy_session hace unset de las variabels?

-->