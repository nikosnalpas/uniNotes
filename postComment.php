<?php
session_start();
require 'db.php';

$noteId = $_REQUEST['noteId'];
$comment = $_REQUEST['comment'];
$idUser = $_SESSION['idUser'];
$result = $mysqli->query("INSERT INTO note_has_comments (users_idusers,notes_idnotes,comment) VALUES ('$idUser' , '$noteId' , '$comment')");

