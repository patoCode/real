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

mysql_select_db($database_frigo, $frigo);
$query_empresas = "SELECT * FROM empresa";
$empresas = mysql_query($query_empresas, $frigo) or die(mysql_error());
$row_empresas = mysql_fetch_assoc($empresas);
$totalRows_empresas = mysql_num_rows($empresas);
?>
<?php require_once('header.php'); ?>
<div id="content">
   <h2>Bienvenido</h2>
   <p>
   	I get a lot of requests about new open source templates, and this one was created as a response to one of those requests. This template is based on the design of my own website, with only small changes (hopefully improvements!) from my own design. Among the changes, it is worth to note that this template does not use any images - and browser-based font resizing is fully supported. Other than that, there are no special features or extras, only valid and well-structured XHTML 1.1 and CSS2 code.

Current template version: 1.0 (November 28, 2005).
   </p>


  <?php do { ?>

      <a href="boleta.php?empresa=<?php echo $row_empresas['idempresa']; ?>"><?php echo $row_empresas['nombre_empresa']?></a>

    <?php } while ($row_empresas = mysql_fetch_assoc($empresas)); ?>
</div>
<div id="subcontent">
<div id="searchbar">
<h2>Buscar Equipo</h2>
<form action="buscador.php" method="POST">
<fieldset>
<input name="key" placeholder="Serie de Equipo" />
<input type="submit" value="Buscar" id="searchbutton" name="searchbutton" />
</fieldset></form>
</div>
</div>
<?php
require_once('footer.php');
mysql_free_result($empresas);
?>
