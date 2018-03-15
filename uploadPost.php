<?php
session_start();
    $dbh = new PDO("mysql:host=localhost;dbname=unuo", "root", "");
    $idClass = $_SESSION['classClicked'];
    $name= $_SESSION['postFileName'];
    $type =  $_SESSION['postFileType'];
    $data =  $_SESSION['postFileData']);
    $imageAbout =  $_SESSION['postAbout'];
    $stmt = $dbh->prepare("INSERT INTO notes (Departments_idDepartments, Universities_idUniversities,Classes_idClasses,users_idusers,image,imageType,imageAbout) VALUES (?,?,?,?,?,?,?)");
     $stmt->bindParam(1,$idDep);
    $stmt->bindParam(2,$idUni);
    $stmt->bindParam(3,$idClass);
    $stmt->bindParam(4,$idUser);
    $stmt->bindParam(5,$data);
    $stmt->bindParam(6,$type);
    $stmt->bindParam(7,$imageAbout);
    $stmt->execute();