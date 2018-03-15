<?php
session_start();
require 'db.php';

$idUser = $_SESSION['idUser'];
$result = $mysqli->query("SELECT * FROM users_has_classes WHERE users_idusers = '$idUser'");

$outp = array();
while ($row = $result->fetch_assoc()) {
    $outp[] = $row;
}
echo json_encode($outp);