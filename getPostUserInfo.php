<?php
session_start();
require 'db.php';
$idUser = $_REQUEST['b'];
$result = $mysqli->query("SELECT idusers,username,profPic,profPicType FROM users WHERE idusers = '$idUser'");

    

$outp = array();
$outp = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($outp);
?>