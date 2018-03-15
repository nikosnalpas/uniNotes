<?php
session_start();
require 'db.php';

$idClass = $_REQUEST['id'];
$idUser = $_SESSION['idUser'];
$result = $mysqli->query("SELECT * FROM users_has_classes WHERE users_idusers = '$idUser' AND Classes_idClasses = '$idClass'");

//check if idCLass exists in result
if($result->num_rows > 0){
	//unfollow / remove from database
	$unfollow = $mysqli->query("DELETE FROM users_has_classes WHERE Classes_idClasses = '$idClass'");
	$_SESSION['follows']-=1;

}else{
	//follow / add in database
	$follow = $mysqli->query("INSERT INTO users_has_classes (users_idusers, Classes_idClasses) VALUES ('$idUser' , '$idClass')");
	$_SESSION['follows']+=1;
}
