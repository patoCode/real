<?php require_once('Connections/frigo.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_boleta_equipo = "-1";
if (isset($_GET['idb'])) {
  $colname_boleta_equipo = $_GET['idb'];
}
mysql_select_db($database_frigo, $frigo);
$query_boleta_equipo = sprintf("SELECT * FROM equipo_has_modelo_boleta WHERE nroBoleta = %s", GetSQLValueString($colname_boleta_equipo, "text"));
$boleta_equipo = mysql_query($query_boleta_equipo, $frigo) or die(mysql_error());
$row_boleta_equipo = mysql_fetch_assoc($boleta_equipo);
$totalRows_boleta_equipo = mysql_num_rows($boleta_equipo);

$colname_simples = "-1";
if (isset($_GET['idb'])) {
  $colname_simples = $_GET['idb'];
}
mysql_select_db($database_frigo, $frigo);
$query_simples = sprintf("SELECT * FROM boleta_rep_simple WHERE nroboleta = %s", GetSQLValueString($colname_simples, "text"));
$simples = mysql_query($query_simples, $frigo) or die(mysql_error());
$row_simples = mysql_fetch_assoc($simples);
$totalRows_simples = mysql_num_rows($simples);

$colname_compuestos = "-1";
if (isset($_GET['idb'])) {
  $colname_compuestos = $_GET['idb'];
}
mysql_select_db($database_frigo, $frigo);
$query_compuestos = sprintf("SELECT * FROM boleta_rep_compuesta WHERE nroboleta = %s", GetSQLValueString($colname_compuestos, "text"));
$compuestos = mysql_query($query_compuestos, $frigo) or die(mysql_error());
$row_compuestos = mysql_fetch_assoc($compuestos);
$totalRows_compuestos = mysql_num_rows($compuestos);
?>
<?php require_once('header.php');?>
<div id="content">
<h2>Detalle Boleta:
<?php echo $row_boleta_equipo['nroBoleta']; ?></h2>
<p>fecha: <?php echo $row_boleta_equipo['fechaBoleta']; ?></p>
<h2>Simples</h2>
<?php do { ?>
  <p><?php 
  $id = $row_simples['idrep_simple'];
  $detalle = mysql_fetch_assoc(mysql_query("SELECT detalle FROM reparacion_simple WHERE idrep_simple = '$id';"));
  echo $detalle['detalle'].'-----';
  echo $row_simples['costo']; ?></p>
  <?php } while ($row_simples = mysql_fetch_assoc($simples)); ?>
  <?php do { ?>
    <p><?php 
	$id = $row_compuestos['idrep_comp'];
  $detalle = mysql_fetch_assoc(mysql_query("SELECT detalle_comp FROM reparacion_compuesta WHERE idrep_comp = '$id';"));
  echo $detalle['detalle_comp'].'-----';
  
	echo $row_compuestos['costo']; ?></p>
    <?php } while ($row_compuestos = mysql_fetch_assoc($compuestos)); ?>
    <p>Costo Total: <?php echo $row_boleta_equipo['totalBoleta']; ?></p>
</div>
<?php require_once('footer.php');?>
<?php
mysql_free_result($boleta_equipo);

mysql_free_result($simples);

mysql_free_result($compuestos);
?>
