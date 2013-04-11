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

$colname_boleta = "-1";
if (isset($_GET['id'])) {
  $colname_boleta = $_GET['id'];
}
mysql_select_db($database_frigo, $frigo);
$query_boleta = sprintf("SELECT * FROM equipo_has_modelo_boleta WHERE equipo_idequipo = %s", GetSQLValueString($colname_boleta, "int"));
$boleta = mysql_query($query_boleta, $frigo) or die(mysql_error());
$row_boleta = mysql_fetch_assoc($boleta);
$totalRows_boleta = mysql_num_rows($boleta);
?>
<?php require_once('header.php');?>
<div id="content">
<h2>Historial del Equipo: <?php echo $_GET['id']?></h2>
<table>
  <tr>
    <th class="separador">Fecha</th>
    <th class="separador" >Boleta</th>
    <th class="separador">Total</th>
    <th class="separador">Detalle</th>
  </tr>
  <?php 
  $total = 0;
  do { ?>
    <tr>
      <td class="resultado"><?php echo $row_boleta['fechaBoleta']; ?></td>
      <td class="resultado"><?php echo $row_boleta['nroBoleta']; ?></td>
      <td class="resultado"><?php 
	  $total = $total + $row_boleta['totalBoleta'];
	  echo $row_boleta['totalBoleta']; ?></td>
      <td class="resultado"><a href="detalle.php?idb=<?php echo $row_boleta['nroBoleta']; ?>">[Ver Detalle]</a></td>
    </tr>
    <?php } while ($row_boleta = mysql_fetch_assoc($boleta)); ?>
    <tr>
      <td>&nbsp;</td>
      <th>TOTAL</th>
      <th><?php echo $total;?></th>
      <td>&nbsp;</td>
    </tr>
    
</table>
</div>
<?php require_once('footer.php');?>
<?php
mysql_free_result($boleta);
?>
