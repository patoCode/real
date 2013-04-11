<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<?php
if($_GET['error']=='0')
   echo "LLEGASTE DESDE EMPRESA";
if($_GET['error']=='1')
  echo "LLEGASTE DESDE NUEVO";
?>
<h1>OK</h1>
<p><a href="inicio.php">inicio</a></p>
</body>
</html>