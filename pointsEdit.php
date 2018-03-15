<?php
session_start();
require 'db.php';

$noteId = $_REQUEST['noteId'];
$points = $_REQUEST['points'];
$idUser = $_SESSION['idUser'];
$add = $_REQUEST['add'];
$upDelete = $mysqli->query("DELETE FROM note_has_upvote WHERE users_idusers = '$idUser' AND notes_idnotes = '$noteId'");
$downDelete = $mysqli->query("DELETE FROM ntoe_has_downvote WHERE users_idusers = '$idUser' AND notes_idnotes = '$noteId'");
if($add==1){
$result = $mysqli->query("INSERT INTO note_has_upvote (users_idusers,notes_idnotes) VALUES ('$idUser' , '$noteId')");
}else if($add==2){
	$result = $mysqli->query("INSERT INTO note_has_downvote (users_idusers,notes_idnotes) VALUES ('$idUser' , '$noteId')");
}
$setPoints = $mysqli->query("UPDATE notes SET points='$points' WHERE idNotes='$noteId'");