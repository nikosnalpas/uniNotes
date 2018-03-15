<?php
require 'db.php';
$q = $_REQUEST['q'];
$uni = $mysqli->query("SELECT idUniversities FROM universities WHERE uniName LIKE '%{$q}%'");

foreach($uni as $u){
	$idUni = $u['idUniversities'];
			    		
}	

$result = $mysqli->query("SELECT depName FROM departments WHERE Universities_idUniversities='$idUni'");
$outp = array();
while ($row = $result->fetch_assoc()) {
    $outp[] = $row;
}
echo json_encode($outp);