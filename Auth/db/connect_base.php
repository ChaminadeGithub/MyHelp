<?php
// connexion a la bdd (declaration des variables du serveur)
$serverName = "localhost";
$dbName = "africd_code" ;
$userName = "root";
$password = "";

try {
    $db = new PDO ("mysql:host=$serverName;dbname=$dbName", $userName, $password) ;
} catch (PDOException $e) {
    die("Oupps ! il ya une erreur".$e->getMessage()) ;
}









?>