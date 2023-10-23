<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connection = "localhost";
$database_connection = "marlowchamb";
$username_connection = "marlowchamb";
$password_connection = "marlowsql2012";
$connection = mysql_pconnect($hostname_connection, $username_connection, $password_connection) or die('Failed');



?>
