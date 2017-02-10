<?php
session_start();

//================== CONFIGURABLE VARIABLES====================//
$servername = 'localhost'; //localhost is the server if run locally
$dbname = 'dw'; //Default is 'bank_transactions'
$username = 'root'; //Default is 'root' for localhost
$password = ''; //Default is NULL for localhost
//=============================================//

try
{
     $DB_con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
     $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
     echo $e->getMessage();
}

include_once 'class.visitor.php';
$visitor = new VISITOR($DB_con);

?>
