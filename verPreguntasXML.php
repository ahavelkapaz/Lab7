<?php
include 'log_f.php';

$preguntas=simplexml_load_file('preguntas.xml');

echo '<div style="padding: 0px 40px 40px 40px"><table class="table table-bordered"><tr><th>Pregunta</th> <th>Complejidad</th><th>Tematica</th></td><tr>';
foreach ($preguntas->assessmentItem as $pregunta) {//$preguntas->xpath('//assessmentItem');
	echo '<tr><td>';
   echo $pregunta->itemBody->p, '</td> <td>  ', $pregunta['complexity'],'</td><td>',$pregunta['subject'],'</td>';
	echo '</tr>';
   }
echo '</table></div>';

	
	registrarLog("ver preguntas");
?>
