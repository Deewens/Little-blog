<?php

include("../pdo/pdo.php");

$pseudoExist = false;

$pseudodouble = $pdo->prepare("SELECT COUNT(*) FROM Redacteur WHERE Pseudo = ?");
$pseudodouble->bindValue(1, $_POST['pseudo'] , PDO::PARAM_STR);
$pseudodouble->execute();

if($pseudodouble->fetchColumn() > 0) {
	$pseudoExist = true;
}

$array = ['pseudoExist' => $pseudoExist];
echo json_encode($array);