<?php

include("../pdo/pdo.php");

$emailExist = false;

$emaildouble = $pdo->prepare("SELECT COUNT(*) FROM Redacteur WHERE AdresseMail = ?");
$emaildouble->bindValue(1, $_POST['email'], PDO::PARAM_STR);
$emaildouble->execute();

if($emaildouble->fetchColumn() > 0) {
	$emailExist = true;
}

$array = ['emailExist' => $emailExist];
echo json_encode($array);