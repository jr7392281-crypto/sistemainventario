<?php
define("SERVIDOR","localhost");
define("USUARIO","root");
define("PASSWORD","");
define("BD","inventario");

$servidor = "mysql:dbname=".BD.";host=".SERVIDOR;

try { 
$pdo = new PDO($servidor, USUARIO, PASSWORD);
//$pdo = new PDO($servidor,USUARIO,PASSWORD);
//echo"Conexion Establlecida";
} catch(PDOException $e){ 
 //print_r($e);
 echo"Error al conectar la base de datos";
}
$URL = "http://localhost/sis-inv";

date_default_timezone_set("America/Lima");
$fechaHora = date('y-m-d H:i:s');
?>