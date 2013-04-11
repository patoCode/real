<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_frigo = "localhost";
$database_frigo = "frigo";
$username_frigo = "root";
$password_frigo = "root";
$frigo = mysql_pconnect($hostname_frigo, $username_frigo, $password_frigo) or trigger_error(mysql_error(),E_USER_ERROR); 
?>