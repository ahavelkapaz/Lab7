<?php

//incluimos la clase nusoap.php
require_once('../lib/nusoap.php');
require_once('../lib/class.wsdlcache.php');

//creamos el objeto de tipo soap_server
//$ns='http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '?wsdl';
$ns='http://ahavelkapaz.esy.es/lab6/ComprobarPassword.php?wsdl';
$server = new soap_server;
$server->configureWSDL('comprobarPass',$ns);
$server->wsdl->schemaTargetNamespace=$ns;
//registramos la función que vamos a implementar
$server->register('comprobarPass',array('x'=>'xsd:string','y'=>'xsd:string'),array('z'=>'xsd:string'),$ns);

//implementamos la función
function comprobarPass($x,$y){
	if($y!='7777'){//Se crearia una tabla en la BD con tickets activos y consultariamos en ella
		return 'USUARIO NO AUTORIZADO';
	}
	$handle = fopen('toppasswords.txt', 'r');
	$encontrado = false;
	while (($buffer = fgets($handle)) !== false) {
		if (trim($buffer)==$x) {
			$encontrado = true;
			break;
		}      
	}
	fclose($handle);


	if($encontrado) return 'INVALIDA';
	else return 'VALIDA';

}
//llamamos al método service de la clase nusoap
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

?>