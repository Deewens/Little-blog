<?php

$connect = 'mysql:host=devbdd.iutmetz.univ-lorraine.fr;dbname=dudon3u_progweb-projet';
$user = 'dudon3u_appli';
$pwd = 'garfield';

	try {
		$pdo = new PDO($connect, $user, $pwd);
	} catch (Exception $exception) {
		die($exception->getMessage());
	}