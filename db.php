<?php
$host = 'sql100.infinityfree.com';  
$user = 'if0_40564705';             
$pass = 'Vetsku33';                
$db   = 'if0_40564705_semifinaalidb';      

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die('Tietokantayhteys epäonnistui: ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');
?>
