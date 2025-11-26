<?php
$host = 'localhost:3308';  
$user = 'root';             
$pass = '';                
$db   = 'semifinaali';      

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die('Tietokantayhteys epäonnistui: ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');
?>
