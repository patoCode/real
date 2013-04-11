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

$idemp_boleta_empresa = "-1";
if (isset($_GET['empresa'])) {
  $idemp_boleta_empresa = $_GET['empresa'];
}
mysql_select_db($database_frigo, $frigo);
$query_boleta_empresa = sprintf("SELECT * FROM modelo_boleta mb, modelo_boleta_has_reparacion_simple mbs, modelo_boleta_has_reparacion_compuesta mbc, empresa e WHERE e.idempresa=%s  and %s=mb.idModelo_Boleta and mb.idModelo_Boleta = mbs.modelo_boleta_idModelo_Boleta and mb.idModelo_Boleta=mbc.modelo_boleta_idModelo_Boleta", GetSQLValueString($idemp_boleta_empresa, "int"),GetSQLValueString($idemp_boleta_empresa, "int"));
$boleta_empresa = mysql_query($query_boleta_empresa, $frigo) or die(mysql_error());
$row_boleta_empresa = mysql_fetch_assoc($boleta_empresa);
$totalRows_boleta_empresa = mysql_num_rows($boleta_empresa);

$boleta_rep_compuestas = "0";
if (isset($row_boleta_empresa['idModelo_Boleta'])) {
  $boleta_rep_compuestas = $row_boleta_empresa['idModelo_Boleta'];
}
mysql_select_db($database_frigo, $frigo);
$query_rep_compuestas = sprintf("SELECT detalle_comp, costo_comp,idrep_comp FROM modelo_boleta_has_reparacion_compuesta mbc, reparacion_compuesta WHERE %s = mbc.Modelo_boleta_idModelo_Boleta AND  mbc.reparacion_compuesta_idrep_comp =  idrep_comp  ", GetSQLValueString($boleta_rep_compuestas, "int"));
$rep_compuestas = mysql_query($query_rep_compuestas, $frigo) or die(mysql_error());
$row_rep_compuestas = mysql_fetch_assoc($rep_compuestas);
$totalRows_rep_compuestas = mysql_num_rows($rep_compuestas);

$simple_rep_simple = "0";
if (isset($row_boleta_empresa['idModelo_Boleta'])) {
  $simple_rep_simple = $row_boleta_empresa['idModelo_Boleta'];
}
mysql_select_db($database_frigo, $frigo);
$query_rep_simple = sprintf("SELECT detalle, costo, idrep_simple FROM modelo_boleta_has_reparacion_simple mbs, reparacion_simple WHERE %s = mbs.Modelo_boleta_idModelo_Boleta AND  mbs.reparacion_simple_idrep_simple =  idrep_simple ", GetSQLValueString($simple_rep_simple, "int"));
$rep_simple = mysql_query($query_rep_simple, $frigo) or die(mysql_error());
$row_rep_simple = mysql_fetch_assoc($rep_simple);
$totalRows_rep_simple = mysql_num_rows($rep_simple);

mysql_select_db($database_frigo, $frigo);
$query_tienda = "SELECT * FROM tienda";
$tienda = mysql_query($query_tienda, $frigo) or die(mysql_error());
$row_tienda = mysql_fetch_assoc($tienda);
$totalRows_tienda = mysql_num_rows($tienda);
?>
<?php require_once('header.php');?>
<div id="content">
<form method="POST" action="realizado.php?opc=1">
<table>
<tr>
   <th colspan="4" class="<?php
   if($row_boleta_empresa['idempresa']==1)
     echo "alg";
   if($row_boleta_empresa['idempresa']==2)
     echo "delizia";
   if($row_boleta_empresa['idempresa']==3)
     echo "coordillera";
   ?>"><?php echo $row_boleta_empresa['nombre_empresa']; ?></th>
</tr>

<tr>
	<td class="tienda" colspan="2"><?php echo $row_tienda['nombreTienda']; ?></td>
	<td class="tienda" colspan="2">
        <?php echo $row_tienda['direccion']; ?><br>
        NIT: <?php echo $row_tienda['factura']; ?>
    </td>
</tr>
<tr>
	<th>Nro. Boleta: </th>
	<td><input type="text" class="input text" name="nroboleta" placeholder="Codigo de Boleta"></td>
    <th>Serie:</th>
	<td><input class="input text" placeholder="Serie del Equipo" name="serie"></td>
</tr>
<tr>
	<th>Cliente:</th>
	<td><input class="input text" placeholder="Cliente..." name="cliente"></td>
    <th>Direccion:</th>
	<td><input class="input text" placeholder="Direccion..." name="direccion"></td>
</tr>
<tr>
    <th>Telefono</th>
    <td><input class="input text" placeholder="Telefono de Referencia" name="telefono"></td>
</tr>
<tr>
    <th colspan="4" class="separador">Reparaciones</th></tr>
        	<?php do { ?>
            <tr>
                <td colspan="2"><input name="compuestos[]" value="<?php echo $row_rep_compuestas['idrep_comp']?>" type="checkbox">
                    	<span></span><?php echo $row_rep_compuestas['detalle_comp']; ?>---<?php echo $row_rep_compuestas['costo_comp']; ?>
				</td></tr>
        	    <?php } while ($row_rep_compuestas = mysql_fetch_assoc($rep_compuestas)); ?>
                
               <?php do { ?>
                <tr>
                  <td colspan="2">
					<input name="simples[]" value="<?php echo $row_rep_simple['idrep_simple']?>" type="checkbox">
                    	<span></span><?php echo $row_rep_simple['detalle']; ?>---<?php echo $row_rep_simple['costo']; ?>
				</td></tr>
      
                <?php } while ($row_rep_simple = mysql_fetch_assoc($rep_simple)); ?>        
<tr>
	<th class="separador" colspan="4">Descripcion:</th></tr>
<tr>    
    <td colspan="4"><textarea class="input textarea" cols="60" placeholder="Descripcion Equipo" name="desc"></textarea></td>
</tr>                
<tr>
  <td>
     <input type="hidden" name="idboleta" value="<?php echo $row_boleta_empresa['idModelo_Boleta']; ?>">
     <input type="submit" value="Aceptar" />
  </td>	
</tr>
</table>
</form>
</div>
<?php require_once('footer.php'); ?>
<?php
mysql_free_result($boleta_empresa);

mysql_free_result($rep_compuestas);

mysql_free_result($rep_simple);

mysql_free_result($tienda);
?>
