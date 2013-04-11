<?php 
require_once("Connections/functions.php");

$opc = $_GET['opc'];
switch($opc){
	case 1: 
	      insert_boleta($_POST['idboleta'],$_POST['nroboleta'],$_POST['cliente'],$_POST['direccion'],$_POST['serie'],$_POST['compuestos'],$_POST['simples'],$_POST['desc'],$_POST['telefono'],'0');
		  header ("Location: mensaje.php?error=0");
		  break;
	case 2:
	insert_boleta($_POST['idboleta'],$_POST['nroboleta'],$_POST['cliente'],$_POST['direccion'],$_POST['ideq'],$_POST['compuestos'],$_POST['simples'],$_POST['desc'],$_POST['telefono'],'1');
	      header ("Location: mensaje.php?error=1");
		  break;
	
}

?>