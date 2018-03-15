<?php
/* Database connection settings */
$host = 'localhost';
$user = 'uninotes_nikos';
$pass = 'Nikos@3145';
$db = 'uninotes_unuo';
$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);
