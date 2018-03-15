<?php
require 'db.php';
$idUser = $_SESSION['idUser'];

$user = $mysqli->query("SELECT * FROM users WHERE idusers = '$idUser'");
foreach($user as $u){
	$username = $u['username'];	 
	$idUni = $u['Universities_idUniversities'];
	$idDep = $u['Departments_idDepartments']; 
	$profPic = $u['profPic'];  	
	$profPicType = $u['profPicType'];	
}	

//University Name
$uni = $mysqli->query("SELECT uniName FROM universities WHERE idUniversities = '$idUni'");
foreach($uni as $u){
	$uniName = $u['uniName'];	    		
}	
$_SESSION['idUni'] = $idUni;
$_SESSION['profileUni'] = $uniName;




//Department Name
$dep = $mysqli->query("SELECT depName FROM departments WHERE idDepartments = '$idDep'");
foreach($dep as $d){
	$depName = $d['depName'];	    		
}	
$_SESSION['idDep'] = $idDep;
$_SESSION['profileDep'] = $depName;



//Username
$_SESSION['profileUsername'] = $username;


//Classes
$result = $mysqli->query("SELECT Classes_idClasses FROM users_has_classes WHERE users_idusers = '$idUser'");
$idClasses = array();
while ($row = $result->fetch_assoc()) {
    $idClasses[] = $row;
}

$_SESSION['profileClasses'] = $idClasses;


//Profile Picture
if($profPic==null){
	$_SESSION['profPic'] = '0';
}else{
    $_SESSION['profPic'] = $profPic;
}

//Profile Picture Type
$_SESSION['profPicType'] = $profPicType;