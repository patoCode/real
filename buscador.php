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

$colname_buscador = "-1";
if (isset($_POST['key'])) {
  $colname_buscador = $_POST['key'];
}
mysql_select_db($database_frigo, $frigo);
$query_buscador = sprintf("SELECT DISTINCT codigo_equipo, idequipo, descripcion_equipo FROM equipo WHERE codigo_equipo LIKE %s", GetSQLValueString("%" . $colname_buscador . "%", "text"));
$buscador = mysql_query($query_buscador, $frigo) or die(mysql_error());
$row_buscador = mysql_fetch_assoc($buscador);
$totalRows_buscador = mysql_num_rows($buscador);
?>
<?php require_once('header.php');?>
<div id="content">
<?php if(!empty($_POST)){?>
<table>
  <tr>
    <th class="separador">Serie</th>
    <th class="separador">Opciones</th>
  </tr>
  <?php do { 
  if(!empty($row_buscador['idequipo'])){
  $id = $row_buscador['idequipo'];
  $sql = mysql_query("SELECT * FROM equipo_has_modelo_boleta eb WHERE equipo_idequipo = $id LIMIT 1");
  $consulta = mysql_fetch_assoc($sql);
  $bol = $consulta['Modelo_boleta_idModelo_Boleta'];
  }
  else{
	  $bol = 0;
	  }
  ?>
    <tr>
      <td class="resultado"><?php echo $row_buscador['codigo_equipo']; ?></td>
      <td class="resultado"> 
      <a href="historial.php?id=<?php echo $row_buscador['idequipo']?>">[Historial]</a> 
      <a href="nuevo.php?id=<?php echo $row_buscador['idequipo']?>&boleta=<?php echo $bol?>">[Nuevo]</a></td>
    </tr>
    <?php } while ($row_buscador = mysql_fetch_assoc($buscador)); ?>
</table>
<?php }?>
</div>
<div id="subcontent">
<div id="searchbar">
<h2>Buscar Equipo</h2>
<form name="form1" method="post" action="">
<fieldset>
<input name="key" placeholder="Serie de Equipo" type="text" />
<input type="submit" value="Buscar" id="searchbutton" name="searchbutton" />
</fieldset>
</form>
</div>
</div>
<?php
require_once('footer.php');
?><?php
mysql_free_result($buscador);
?>
