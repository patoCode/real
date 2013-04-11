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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "empresa")) {
  $insertSQL = sprintf("INSERT INTO tienda (factura, nombreTienda, dueno_tienda, direccion, telefono) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['NIT'], "text"),
                       GetSQLValueString($_POST['tienda'], "text"),
                       GetSQLValueString($_POST['dueno'], "text"),
                       GetSQLValueString($_POST['direccion'], "text"),
                       GetSQLValueString($_POST['telefono'], "text"));

  mysql_select_db($database_frigo, $frigo);
  $Result1 = mysql_query($insertSQL, $frigo) or die(mysql_error());

  $insertGoTo = "inicio.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/gumby.css" rel="stylesheet" type="text/css">
<title></title>
</head>
<body>
<div class="container">
<div class="row">
<div class="five columns centered">
 <h2 class="subhead">Datos de la Empresa</h2>
   <form action="<?php echo $editFormAction; ?>" name="empresa" method="POST" id="empresa">
     <ul>
     	<li class="field"><input class="input text" placeholder="Gerente Empresa" type="text" name="dueno"><li>        <li class="field"><input class="input text" placeholder="Nombre Empresa" type="text" name="tienda"></li>
     	<li class="field"><input class="input text" placeholder="NIT" type="text" name="NIT"></li>
        
     	<li class="field"><input class="input text" placeholder="Telefono" type="text" name="telefono"><li>
        
        <li class="field"><input class="input text" placeholder="Direccion" type="text" name="direccion"><li>
        

              	<div class="medium secondary btn"><input type="submit" value="Actualizar" /></div>
     </ul>
     <input type="hidden" name="MM_insert" value="empresa">
   </form> 
</div>
</div>   
</div> 
</body>
</html>