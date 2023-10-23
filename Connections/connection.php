<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"


$hostname_connection = "localhost";
$database_connection = "Charter";
$username_connection = "root";
$password_connection = "1234";
// $connection = mysql_connect($hostname_connection,$username_connection,$password_connection,$database_connection); 

$connection = mysqli_connect( $hostname_connection, $username_connection , $password_connection , $database_connection );





?>
