<?php
session_start();
require 'db.php';
$q = $_REQUEST['q'];
$_SESSION['idClicked'] = $q;

?>