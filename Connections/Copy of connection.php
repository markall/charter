<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connection = "localhost";
$database_connection = "hsccharter";
$username_connection = "hsccharter";
$password_connection = "charter";
$connection = mysql_pconnect($hostname_connection, $username_connection, $password_connection) or die(mysql_error());


?>
