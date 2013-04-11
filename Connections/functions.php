<?php 
  require_once("frigo.php");
  
  function insert_boleta($idboleta,$nroboleta, $cliente, $dir, $serie, $compuesto, $simple, $desc, $telefono,$opc){

	 if($opc=='0')
	 {
	    $ins_equipo = mysql_query("INSERT INTO equipo VALUES('','$serie','$desc')");
        $equipo = mysql_fetch_assoc(mysql_query("SELECT idequipo FROM equipo WHERE codigo_equipo='$serie';"));
	    $idequipo = $equipo['idequipo'];	 
	 }
	 if($opc=='1')
	 {
		$idequipo = $serie; 
		 }
	 
	 
	 $hoy = date("Y-m-d");
	 
	 $separado = explode(' ',$cliente);
	 $nombre = $separado[0];
	 $ap = $separado[1].' '.$separado[2];	
	 $ins_cliente = mysql_query("INSERT INTO cliente VALUES('','$nombre','$ap','$dir','$telefono')");
	 
	 $data_cliente = mysql_fetch_assoc(mysql_query("SELECT idcliente FROM cliente WHERE nombre_cliente='$nombre' AND apellido_cliente='$ap';"));
	 $idcliente = $data_cliente['idcliente'];
	 
	 $ins_equipo_cliente = mysql_query("INSERT INTO equipo_has_cliente VALUES('$idequipo', '$idcliente')");
	 
	 
	 /* INSERCION DE REPARACIONES COMPUESTAS */
	 $total = 0;
	 for($i=0; $i < sizeof($compuesto); $i++){

		  $dato =$compuesto[$i];
		  $reparacion = mysql_fetch_assoc(mysql_query("SELECT costo_comp FROM reparacion_compuesta WHERE idrep_comp= '$dato';"));
		  $costo = $reparacion['costo_comp'];
  		  $total = $total+$costo;
		  mysql_query("INSERT INTO boleta_rep_compuesta VALUES('$idboleta','$dato','$costo', '$nroboleta')");
		  		  
	}
	
	/* INSERCION DE REAPARACIONES SIMPLES */
	 for($i=0; $i < sizeof($simple); $i++){

		  $dato =$simple[$i];
		  $reparacion = mysql_fetch_assoc(mysql_query("SELECT costo FROM reparacion_simple WHERE idrep_simple= '$dato';"));
		  $costo = $reparacion['costo'];
  		  $total = $total+$costo;
		  mysql_query("INSERT INTO boleta_rep_simple VALUES('$idboleta','$dato','$costo', '$nroboleta')");
		  		  
	} 
	
	 $ins_boleta = mysql_query("INSERT INTO equipo_has_Modelo_boleta VALUES('$idequipo','$idboleta', '$hoy', '$total', '$nroboleta' )");
	 
  }
  
  
?>