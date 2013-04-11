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
if (isset($_GET['boleta'])) {
  $colname_boleta = $_GET['boleta'];
}
mysql_select_db($database_frigo, $frigo);
$query_boleta = sprintf("SELECT * FROM modelo_boleta WHERE idModelo_Boleta = %s", GetSQLValueString($colname_boleta, "int"));
$boleta = mysql_query($query_boleta, $frigo) or die(mysql_error());
$row_boleta = mysql_fetch_assoc($boleta);
$totalRows_boleta = mysql_num_rows($boleta);

$colname_equipo = "-1";
if (isset($_GET['id'])) {
  $colname_equipo = $_GET['id'];
}
mysql_select_db($database_frigo, $frigo);
$query_equipo = sprintf("SELECT * FROM equipo WHERE idequipo = %s", GetSQLValueString($colname_equipo, "int"));
$equipo = mysql_query($query_equipo, $frigo) or die(mysql_error());
$row_equipo = mysql_fetch_assoc($equipo);
$totalRows_equipo = mysql_num_rows($equipo);

$colname_compuesto = "-1";
if (isset($_GET['boleta'])) {
  $colname_compuesto = $_GET['boleta'];
}
mysql_select_db($database_frigo, $frigo);
$query_compuesto = sprintf("SELECT rc.idrep_comp, costo_comp, detalle_comp FROM modelo_boleta_has_reparacion_compuesta mbc, reparacion_compuesta rc WHERE mbc.Modelo_boleta_idModelo_Boleta = %s AND rc.idrep_comp=mbc.reparacion_compuesta_idrep_comp", GetSQLValueString($colname_compuesto, "int"));
$compuesto = mysql_query($query_compuesto, $frigo) or die(mysql_error());
$row_compuesto = mysql_fetch_assoc($compuesto);
$totalRows_compuesto = mysql_num_rows($compuesto);

$colname_simple = "-1";
if (isset($_GET['boleta'])) {
  $colname_simple = $_GET['boleta'];
}
mysql_select_db($database_frigo, $frigo);
$query_simple = sprintf("SELECT rs.idrep_simple, costo, detalle FROM modelo_boleta_has_reparacion_simple mbs, reparacion_simple rs WHERE mbs.Modelo_boleta_idModelo_Boleta = %s AND mbs.reparacion_simple_idrep_simple = rs.idrep_simple", GetSQLValueString($colname_simple, "int"));
$simple = mysql_query($query_simple, $frigo) or die(mysql_error());
$row_simple = mysql_fetch_assoc($simple);
$totalRows_simple = mysql_num_rows($simple);
?>
<?php require_once('header.php');?>
<div id="content">

<!--   insert_boleta($_POST['idboleta'],$_POST['nroboleta'],$_POST['cliente'],$_POST['direccion'],$_POST['serie'],$_POST['compuestos'],$_POST['simples'],$_POST['desc'],$_POST['telefono']);
 -->
<form action="realizado.php?opc=2" method="POST">
<table>
<input type="hidden" name="idboleta" value="<?php echo $_GET['boleta']?>" />
<input type="hidden" name="ideq" value="<?php echo $_GET['id']; ?>"/>
<tr>
<th colspan="4" class="nueva"><h2>NUEVA REPARACION</h2></th>
</tr>
<tr>
  <th>Equipo:</th><td> <?php echo $row_equipo['codigo_equipo']; ?></td>

  <th>Nro Boleta:</th>
  <td><input type="text" name="nroboleta" placeholder="Nro Boleta..." /></td>
</tr>
<tr>
	<th>Cliente:</th>
    <td><input type="text" name="cliente" placeholder="Cliente..." /></td>
    <th>Telefono</th>
  <td><input type="text" name="telefono" placeholder="Telefono..." /></td>
</tr>
<tr>
    <th>Direccion:</th>
    <td><input type="text" name="direccion" placeholder="Direccion..." /></td>
</tr>
<tr>
   <th class="separador" colspan="4">Reparaciones</th>
</tr>
<?php do { ?>
<tr>
  <td colspan="4">
    <input type="checkbox" name="compuestos[]" id="checkbox" value="<?php echo $row_compuesto['idrep_comp']?>"	/>
    <?php echo $row_compuesto['detalle_comp']; ?> ----&gt; <?php echo $row_compuesto['costo_comp']; ?></label>
  </td>
</tr>
  <?php } while ($row_compuesto = mysql_fetch_assoc($compuesto)); ?>

  <?php do { ?>
<tr>
  <td colspan="4">
      <input type="checkbox" name="simples[]" value="<?php echo $row_simple['idrep_simple']; ?>" />
      <?php echo $row_simple['detalle']; ?>
    -----&gt; <?php echo $row_simple['costo']; ?>
   </td>
</tr> 
    <?php } while ($row_simple = mysql_fetch_assoc($simple)); ?>
<tr>
   <th class="separador" colspan="4">Descripcion</th>
</tr>
<tr>
  <td colspan="4">
  <label for="descripcion">Descripcion:</label><textarea name="desc" cols="60" placeholder="Descripcion..."></textarea>
  </td>
</tr>
<tr>
<td>
<input type="submit" value="Agregar" />
</td>
</tr>
</table>
</form>
</div>
<?php require_once('footer.php');?>
<?php
mysql_free_result($boleta);

mysql_free_result($equipo);

mysql_free_result($compuesto);

mysql_free_result($simple);
?>
