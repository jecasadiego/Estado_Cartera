<?php
// Enter your Host, username, password, database below.

$hostname = "sqlsrv:server=10.100.1.175\BDINVESAKK1";
$username = 'consultas';
$password = 'Php#1002';
$database = 'Database=invesakk';

// $con = mysqli_connect($hostname, $username, $password, $database);
// 	if (mysqli_connect_errno()){
// 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
// 		die();
// 		}


//Conexión con PDO
$opciones = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"];
try {
    $con = new PDO($hostname.'; '.$database, $username, $password, $opciones);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Todo bien";
} catch (PDOException $e) {
    echo 'Falló la conexión: ' . $e->getMessage();
}
