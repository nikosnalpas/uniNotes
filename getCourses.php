<?php
require 'db.php';

$c = $_REQUEST['c'];

$dep = $mysqli->query("SELECT idDepartments FROM departments WHERE depName LIKE '%{$c}%'");

foreach($dep as $d){
	$idDep = $d['idDepartments'];	    		
}	


$result = $mysqli->query("SELECT * FROM classes WHERE Departments_idDepartments='$idDep'");


$outp = array();
while ($row = $result->fetch_assoc()) {
    $outp[] = $row;
}
echo json_encode($outp);

?>