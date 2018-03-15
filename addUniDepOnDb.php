<?php
session_start();
require 'db.php';

$dep = $_REQUEST['depSelected'];
$uni = $_REQUEST['uniSelected'];
$idUser = $_SESSION['idUser'];
$_SESSION['idClicked'] = $idUser;


$uniName = $mysqli->query("SELECT idUniversities FROM universities WHERE uniName LIKE '%{$uni}%'");
foreach($uniName as $u){
	$idUni = $u['idUniversities'];	    		
}	


$depName = $mysqli->query("SELECT idDepartments FROM departments WHERE depName LIKE '%{$dep}%'");
foreach($depName as $d){
	$idDep = $d['idDepartments'];	    		
}	


$result = $mysqli->query("UPDATE users SET Departments_idDepartments='$idDep' , Universities_idUniversities = '$idUni' WHERE idusers='$idUser'");
